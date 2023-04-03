<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class Defaults extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'                  => 'Admin',
            'email'                 => 'admin@metinnerbek.com',
            'password'              =>  Hash::make('123123'),
            'role'                  => 'admin'
        ]);
    }
}
