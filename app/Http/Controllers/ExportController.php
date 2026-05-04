<?php

namespace App\Http\Controllers;

use App\Models\PerangkatAplikasi;
use App\Models\Schedule;
use App\Models\PicItSupport;
use ZipArchive;

class ExportController extends Controller
{
    // ══════════════════════════════════════════════════════════════
    // EXPORT PERANGKAT APLIKASI → XLSX (OpenXML via ZipArchive)
    // ══════════════════════════════════════════════════════════════
    public function perangkatAplikasi()
    {
        $rows = PerangkatAplikasi::with('creator')->orderBy('id', 'desc')->get();

        $patchLabels = [
            '✅' => 'Up-to-date',
            '❌' => 'Belum Up-to-date',
            '–'  => 'Tidak relevan',
            '⌛' => 'Belum Konfirmasi',
        ];

        $headers = [
            'No', 'Nama Perangkat', 'URL', 'IP Address', 'Brand', 'Type',
            'Server', 'OS', 'Lokasi', 'Bidang', 'MSB / Sub Bidang',
            'Firmware Patch', 'Network Device Patch', 'Pemilik Aset',
            'Dibuat Oleh', 'Dibuat Pada',
        ];

        $colWidths = [5, 22, 28, 15, 14, 16, 18, 20, 22, 14, 28, 16, 20, 20, 14, 22];
        $cols = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P'];
        $lastCol = $cols[count($headers) - 1];

        // ── XML helpers ──
        $xe = fn(string $v): string => htmlspecialchars(
            preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $v),
            ENT_QUOTES | ENT_XML1, 'UTF-8'
        );
        $strCell = fn(string $ref, string $val, int $s): string =>
            '<c r="'.$ref.'" s="'.$s.'" t="inlineStr"><is><t>'.$xe($val).'</t></is></c>';
        $numCell = fn(string $ref, int $val, int $s): string =>
            '<c r="'.$ref.'" s="'.$s.'"><v>'.$val.'</v></c>';

        $patchStyle = function(string $val, bool $isEven): int {
            $base = match($val) { '✅' => 4, '❌' => 6, '–' => 8, default => 10 };
            return $isEven ? $base + 1 : $base;
        };

        // ── Static XML parts ──
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            .'<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            .'<Default Extension="xml" ContentType="application/xml"/>'
            .'<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            .'<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            .'<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            .'</Types>';

        $topRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            .'</Relationships>';

        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            .'<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            .'<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            .'</Relationships>';

        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            .'<sheets><sheet name="Perangkat Aplikasi" sheetId="1" r:id="rId1"/></sheets></workbook>';

        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            .'<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            .'<fonts count="5">'
            .'<font><sz val="10"/><name val="Arial"/></font>'
            .'<font><b/><sz val="10"/><color rgb="FFFFFFFF"/><name val="Arial"/></font>'
            .'<font><b/><sz val="13"/><color rgb="FF1E293B"/><name val="Arial"/></font>'
            .'<font><sz val="9"/><color rgb="FF64748B"/><name val="Arial"/></font>'
            .'<font><b/><sz val="10"/><name val="Arial"/></font>'
            .'</fonts>'
            .'<fills count="10">'
            .'<fill><patternFill patternType="none"/></fill>'
            .'<fill><patternFill patternType="gray125"/></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FF1E40AF"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF1F5F9"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFD1FAE5"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFFEE2E2"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFF1F5F9"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFFEF3C7"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFEBFDF4"/><bgColor indexed="64"/></patternFill></fill>'
            .'<fill><patternFill patternType="solid"><fgColor rgb="FFFFF7ED"/><bgColor indexed="64"/></patternFill></fill>'
            .'</fills>'
            .'<borders count="2">'
            .'<border><left/><right/><top/><bottom/><diagonal/></border>'
            .'<border><left style="thin"><color rgb="FFE2E8F0"/></left><right style="thin"><color rgb="FFE2E8F0"/></right><top style="thin"><color rgb="FFE2E8F0"/></top><bottom style="thin"><color rgb="FFE2E8F0"/></bottom><diagonal/></border>'
            .'</borders>'
            .'<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            .'<cellXfs>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment vertical="center" wrapText="1"/></xf>'
            .'<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0"><alignment horizontal="center" vertical="center" wrapText="1"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment vertical="center" wrapText="1"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0"><alignment vertical="center" wrapText="1"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="4" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="4" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="5" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="5" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="6" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="6" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="7" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="4" fillId="7" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="0" fillId="3" borderId="1" xfId="0"><alignment horizontal="center" vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="2" fillId="0" borderId="0" xfId="0"><alignment vertical="center"/></xf>'
            .'<xf numFmtId="0" fontId="3" fillId="0" borderId="0" xfId="0"><alignment vertical="center"/></xf>'
            .'</cellXfs>'
            .'</styleSheet>';

        // ── Build Sheet XML ──
        $s  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $s .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">';
        $s .= '<sheetViews><sheetView workbookViewId="0"><pane ySplit="4" topLeftCell="A5" activePane="bottomLeft" state="frozen"/></sheetView></sheetViews>';
        $s .= '<cols>';
        foreach ($colWidths as $i => $w) { $ci = $i + 1; $s .= '<col min="'.$ci.'" max="'.$ci.'" width="'.$w.'" customWidth="1"/>'; }
        $s .= '</cols><sheetData>';

        // Title
        $s .= '<row r="1" ht="28" customHeight="1">'.$strCell('A1', 'Data Perangkat Aplikasi - PLN UID JATENG DIY', 14).'</row>';
        $s .= '<row r="2" ht="16" customHeight="1">'.$strCell('A2', 'Diekspor: '.now()->format('d/m/Y H:i').' WIB  |  Total: '.$rows->count().' data', 15).'</row>';
        $s .= '<row r="3" ht="4" customHeight="1"></row>';

        // Headers
        $s .= '<row r="4" ht="30" customHeight="1">';
        foreach ($headers as $i => $h) $s .= $strCell($cols[$i].'4', $h, 1);
        $s .= '</row>';

        // Data
        $no = 1; $rowNum = 5;
        foreach ($rows as $r) {
            $isEven = ($no % 2 === 0);
            $rs = $isEven ? 3 : 2;
            $ns = $isEven ? 13 : 12;
            $fp = (string)($r->firmware_patch ?? '⌛');
            $np = (string)($r->network_device_patch ?? '⌛');

            $s .= '<row r="'.$rowNum.'" ht="18" customHeight="1">';
            $s .= $numCell('A'.$rowNum, $no, $ns);
            $s .= $strCell('B'.$rowNum, (string)($r->jenis_perangkat ?? ''), $rs);
            $s .= $strCell('C'.$rowNum, (string)($r->url ?? ''), $rs);
            $s .= $strCell('D'.$rowNum, (string)($r->ip ?? ''), $rs);
            $s .= $strCell('E'.$rowNum, (string)($r->brand ?? ''), $rs);
            $s .= $strCell('F'.$rowNum, (string)($r->type ?? ''), $rs);
            $s .= $strCell('G'.$rowNum, (string)($r->server ?? ''), $rs);
            $s .= $strCell('H'.$rowNum, (string)($r->os ?? ''), $rs);
            $s .= $strCell('I'.$rowNum, (string)($r->lokasi ?? ''), $rs);
            $s .= $strCell('J'.$rowNum, (string)($r->bidang ?? ''), $rs);
            $s .= $strCell('K'.$rowNum, (string)($r->msb_sub_bidang ?? ''), $rs);
            $s .= $strCell('L'.$rowNum, $patchLabels[$fp] ?? 'Belum Konfirmasi', $patchStyle($fp, $isEven));
            $s .= $strCell('M'.$rowNum, $patchLabels[$np] ?? 'Belum Konfirmasi', $patchStyle($np, $isEven));
            $s .= $strCell('N'.$rowNum, (string)($r->pemilik_aset ?? ''), $rs);
            $s .= $strCell('O'.$rowNum, (string)($r->creator->username ?? ''), $rs);
            $s .= $strCell('P'.$rowNum, (string)($r->created_at ?? ''), $rs);
            $s .= '</row>';
            $no++; $rowNum++;
        }

        $s .= '</sheetData>';
        $s .= '<mergeCells count="2"><mergeCell ref="A1:'.$lastCol.'1"/><mergeCell ref="A2:'.$lastCol.'2"/></mergeCells>';
        $s .= '</worksheet>';

        // ── Build XLSX via ZipArchive ──
        $tmp = storage_path('app/pa_export_'.uniqid().'.xlsx');
        $zip = new ZipArchive();
        if ($zip->open($tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Gagal membuat file export.');
        }

        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $topRels);
        $zip->addFromString('xl/workbook.xml', $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);
        $zip->addFromString('xl/styles.xml', $styles);
        $zip->addFromString('xl/worksheets/sheet1.xml', $s);
        $zip->close();

        $filename = 'perangkat_aplikasi_'.now()->format('Ymd_His').'.xlsx';
        return response()->download($tmp, $filename)->deleteFileAfterSend(true);
    }

    // ══════════════════════════════════════════════════════════════
    // EXPORT DATA JADWAL → CSV
    // ══════════════════════════════════════════════════════════════
    public function dataJadwal()
    {
        $rows = Schedule::orderBy('created_at', 'asc')->get();

        $headers = [
            'No', 'Transaction ID', 'Start Date', 'End Date', 'PIC Acara',
            'Nama Acara', 'PIC IT Support', 'Meeting Room', 'Pelaksanaan',
            'Standby Status', 'Kebutuhan Detail', 'Tindak Lanjut', 'Created At',
        ];

        $callback = function () use ($rows, $headers) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers, ';');

            $no = 1;
            foreach ($rows as $r) {
                $picIt = $r->pic_it_support;
                if (is_string($picIt)) {
                    $decoded = json_decode($picIt, true);
                    $picIt = is_array($decoded) ? implode(', ', $decoded) : $picIt;
                }

                fputcsv($out, [
                    $no++,
                    $r->transaction_id,
                    $r->start_date ? $r->start_date->format('Y-m-d') : '',
                    $r->end_date ? $r->end_date->format('Y-m-d') : '',
                    $r->pic_acara,
                    $r->nama_acara,
                    $picIt,
                    $r->meeting_room,
                    $r->pelaksanaan,
                    $r->standby_status,
                    $r->kebutuhan_detail,
                    $r->tindak_lanjut,
                    $r->created_at,
                ], ';');
            }
            fclose($out);
        };

        $filename = 'jadwal_kegiatan_'.now()->format('Ymd').'.csv';
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-Control' => 'max-age=0, no-store',
        ]);
    }
}
