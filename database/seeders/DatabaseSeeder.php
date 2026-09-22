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

        Usuario::updateOrCreate(
            ['email' => env('SERVIDOR_EMAIL', 'servidor@sdp.local')],
            [
                'nome' => env('SERVIDOR_NAME', 'Servidor do SDP'),
                'matricula' => env('SERVIDOR_MATRICULA', 'SERVIDOR001'),
                'password' => Hash::make(env('SERVIDOR_PASSWORD', 'servidor123')),
                'role' => 'professor',
            ]
        );

        Usuario::updateOrCreate(
            ['email' => env('ALUNO_EMAIL', 'aluno@sdp.local')],
            [
                'nome' => env('ALUNO_NAME', 'Aluno de Teste'),
                'matricula' => env('ALUNO_MATRICULA', 'ALUNO001'),
                'password' => Hash::make(env('ALUNO_PASSWORD', 'aluno123')),
                'role' => 'aluno',
            ]
        );

        $this->call(ModeloRequerimentoSeeder::class);
    }
}
