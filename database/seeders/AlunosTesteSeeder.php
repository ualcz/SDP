<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AlunosTesteSeeder extends Seeder
{
    public function run(): void
    {
        $alunos = [
            [
                'matricula' => '20101180001',
                'nome' => 'Aluno Teste Informatica 2010',
                'email' => 'aluno.teste.2010.18@sdp.local',
                'turma_codigo' => '20101.1.18.1N',
            ],
            [
                'matricula' => '20101SEAADS0001',
                'nome' => 'Aluno Teste SEAADS 2010',
                'email' => 'aluno.teste.2010.seaads@sdp.local',
                'turma_codigo' => '20101.1.SEAADS.1N',
            ],
            [
                'matricula' => '20101280001',
                'nome' => 'Aluno Teste Meio Ambiente 2010',
                'email' => 'aluno.teste.2010.28@sdp.local',
                'turma_codigo' => '20101.1.28.1N',
            ],
            [
                'matricula' => '20101COCIE0001',
                'nome' => 'Aluno Teste COCIE 2010',
                'email' => 'aluno.teste.2010.cocie@sdp.local',
                'turma_codigo' => '20101.1.COCIE.1N',
            ],
        ];

        foreach ($alunos as $aluno) {
            Usuario::updateOrCreate(
                ['matricula' => $aluno['matricula']],
                [
                    ...$aluno,
                    'password' => Hash::make('aluno123'),
                    'role' => 'aluno',
                ]
            );
        }
    }
}