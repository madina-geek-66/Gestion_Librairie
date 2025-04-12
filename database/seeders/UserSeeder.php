<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        User::create([
//            'nom' => 'Diallo',
//            'prenom' => 'Madiina',
//            'telephone' => '+221764979418',
//            'adresse' => 'Keur Massar',
//            'email' => 'contact@connect2profit.com',
//            'password' => Hash::make('madinaD12#'),
//        ]);

        Role::create([
            'name' => 'user',
            'guard_name' => 'web'
        ]);

//        $admin = Role::create(['name' => 'admin']);
//        User::first()->assignRole('admin');
    }
}
