<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionType::create(['id' => 1, 'type_name' => 'INGRESO']);
        TransactionType::create(['id' => 2, 'type_name' => 'AJUSTE']);
        TransactionType::create(['id' => 3, 'type_name' => 'ENCARGO']);
        TransactionType::create(['id' => 4, 'type_name' => 'DESCARGO']);
        TransactionType::create(['id' => 5, 'type_name' => 'CAMBIO DE UBICACION']);
    }
}
