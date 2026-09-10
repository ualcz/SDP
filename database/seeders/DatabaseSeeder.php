<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Usuario::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@sdp.local')],
            [
                'nome' => env('ADMIN_NAME', 'Administrador do SDP'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'role' => 'admin',
                'matricula' => null,
            ]
        );

        $this->call(ModeloRequerimentoSeeder::class);
    }
}
