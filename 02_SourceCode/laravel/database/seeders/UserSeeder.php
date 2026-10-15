<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('SEED_TEST_PASSWORD');

        if (
            !is_string($password)
            || strlen($password) < 10
            || !preg_match('/^[A-Za-z0-9]+$/', $password)
        ) {
            throw new \RuntimeException(
                'SEED_TEST_PASSWORD trong .env phai co it nhat 10 ky tu chu/so.'
            );
        }

        $now = now();

        $testHash = Hash::make($password);
        $simulatedHash = Hash::make(Str::random(40));

        $rows = [];

        // Bốn tài khoản kiểm thử với các vai trò khác nhau
        $accounts = [
            [1, 'Quan tri he thong', 'admin@baotang360.test', 'admin'],
            [2, 'Quan ly noi dung', 'manager@baotang360.test', 'manager'],
            [3, 'Doi tac bao tang', 'partner@baotang360.test', 'partner'],
            [4, 'Khach hang dang ky', 'user@baotang360.test', 'user'],
        ];

        foreach ($accounts as [$id, $name, $email, $role]) {
            $rows[] = [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'email_verified_at' => $now,
                'password' => $testHash,
                'role' => $role,
                'status' => 'active',
                'locale' => 'vi',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // 40 tài khoản mô phỏng, không dùng cho người dùng thật
        for ($i = 1; $i <= 40; $i++) {
            $rows[] = [
                'id' => 4 + $i,
                'name' => sprintf('Khach mo phong %03d', $i),
                'email' => sprintf(
                    'khach%03d@baotang360.test',
                    $i
                ),
                'email_verified_at' => $now,
                'password' => $simulatedHash,
                'role' => 'user',
                'status' => 'active',
                'locale' => $i % 2 === 0 ? 'en' : 'vi',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('users')->upsert(
                $chunk,
                ['id'],
                [
                    'name',
                    'email',
                    'email_verified_at',
                    'password',
                    'role',
                    'status',
                    'locale',
                    'updated_at',
                ]
            );
        }
    }
}
