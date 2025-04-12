<?php

namespace Database\Seeders;

use App\Models\Auteur;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Auteur::create(['nom' => 'Baudelaire', 'prenom' => 'Charles']);
        Auteur::create(['nom' => 'Apollinaire', 'prenom' => 'Guillaume']);
        Auteur::create(['nom' => 'Prévert', 'prenom' => 'Jacques']);
        Auteur::create(['nom' => 'Hugo', 'prenom' => 'Victor']);
    }
}
