<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HashExistingPasswords extends Command
{
    protected $signature = 'users:hash-passwords';
    protected $description = 'Hash all existing plain_password values in the users table';

    public function handle()
    {
        $users = DB::table('users')->whereNotNull('plain_password')->where('plain_password', '!=', '')->get();

        if ($users->isEmpty()) {
            $this->info('No users with plain passwords found.');
            return 0;
        }

        $this->info("Found {$users->count()} users with plain passwords. Hashing...");

        $bar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($user->plain_password),
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('All passwords have been hashed successfully!');
        $this->warn('Note: plain_password column is kept for reference. You may remove it later.');

        return 0;
    }
}
