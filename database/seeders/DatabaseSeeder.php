<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ──────────────────────────────────────────
        DB::table('users')->insertOrIgnore([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bagian' => 'IT',
            'is_active' => 1,
        ]);

        // ── Default Meeting Rooms ───────────────────────────────
        $rooms = [
            'R. GM', 'R. LAWANG SEWU LT 4', 'R. RAPAT SRM KU',
            'R. RAPAT SRM DIST', 'R. RAPAT DIENG', 'R. PRAMBANAN LT 3',
            'R. BOROBUDUR LT 2', 'R. RAPAT SRM SDM',
        ];
        foreach ($rooms as $room) {
            DB::table('meeting_rooms')->insertOrIgnore([
                'name' => $room, 'is_active' => 1,
            ]);
        }

        // ── Default PIC IT Support ──────────────────────────────
        $pics = [
            'Safno TJLS', 'andik HTD', 'ANI HTD', 'satria award',
        ];
        foreach ($pics as $pic) {
            DB::table('pic_it_supports')->insertOrIgnore([
                'name' => $pic, 'is_active' => 1,
            ]);
        }

        // ── Default Zoom Units ──────────────────────────────────
        $units = [
            'STI', 'PERENCANAAN', 'HUKUM', 'FUNGSIONAL AHLI', 'KKU',
            'NIAGA', 'KEUANGAN', 'DISTRIBUSI', 'UP2K', 'SDM',
            'KOMUNIKASI', 'PENGADAAN', 'UP3 SEMARANG', 'UP3 SURAKARTA',
            'UP3 PURWOKERTO', 'UP3 TEGAL', 'UP3 MAGELANG',
            'UP3 YOGYAKARTA', 'UP3 PEKALONGAN', 'UP3 KUDUS',
            'UP3 KLATEN', 'UP3 DEMAK', 'UP3 CILACAP',
            'ULP/UP2K', 'UID JAWA TENGAH & D.I. YOGYAKARTA',
            'LAINNYA', 'K3L',
        ];
        foreach ($units as $unit) {
            DB::table('zoom_units')->insertOrIgnore([
                'name' => $unit, 'is_active' => 1,
            ]);
        }

        // ── Default Zoom Links ──────────────────────────────────
        $zoomLinks = [];
        for ($i = 1; $i <= 9; $i++) {
            $zoomLinks[] = 'zoomplnuidjty00'.$i.'@gmail.com';
        }
        $zoomLinks[] = 'zoomplnuidjty0066@gmail.com';

        foreach ($zoomLinks as $email) {
            DB::table('zoom_links')->insertOrIgnore([
                'email' => $email, 'is_active' => 1,
            ]);
        }

        // ── Default Perangkat Aplikasi Master Data ──────────────
        $masterData = [
            'perangkat_aplikasi_jenis' => [
                'PC AIO', 'PRINTER', 'SWITCH', 'ROUTER', 'FIREWALL',
                'SERVER', 'ACCESS POINT', 'UPS', 'MONITOR', 'LAPTOP',
            ],
            'perangkat_aplikasi_brands' => [
                'HP', 'DELL', 'LENOVO', 'CISCO', 'MIKROTIK',
                'FORTINET', 'ARUBA', 'TP-LINK', 'APC', 'ASUS',
            ],
            'perangkat_aplikasi_lokasi' => [
                'KANTOR INDUK', 'UP3 SEMARANG', 'UP3 SURAKARTA',
                'UP3 PURWOKERTO', 'UP3 TEGAL', 'UP3 YOGYAKARTA',
                'LAWANG SEWU', 'GUDANG',
            ],
            'perangkat_aplikasi_bidang' => [
                'STI', 'SDM', 'KEUANGAN', 'DISTRIBUSI', 'NIAGA',
                'PERENCANAAN', 'HUKUM', 'KOMUNIKASI',
            ],
            'perangkat_aplikasi_msb' => [
                'MSB JARINGAN', 'MSB INFRASTRUKTUR', 'MSB APLIKASI',
                'MSB KEAMANAN INFORMASI', 'SUB BIDANG OPERASIONAL',
            ],
        ];

        foreach ($masterData as $table => $items) {
            foreach ($items as $item) {
                DB::table($table)->insertOrIgnore([
                    'name' => $item, 'is_active' => 1,
                ]);
            }
        }

        $this->command->info('✅ Database seeded successfully!');
    }
}
