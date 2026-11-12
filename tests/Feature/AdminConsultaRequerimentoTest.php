<?php

use App\Models\Setor;
use App\Models\Usuario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id();
        $table->string('matricula')->nullable();
        $table->string('nome');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('turma_codigo')->nullable();
        $table->string('role')->default('aluno');
        $table->rememberToken();
        $table->timestamps();
    });

    Schema::create('setores', function (Blueprint $table) {
        $table->id();
        $table->string('setor_sigla');
        $table->string('setor_nome');
        $table->string('titulo');
        $table->boolean('ativo')->default(true);
        $table->timestamps();
    });

    Schema::create('setor_responsavel', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('setor_id');
        $table->unsignedBigInteger('usuario_id');
        $table->timestamps();
    });

    Schema::create('assuntos_requerimentos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('setor_id');
        $table->string('descricao');
        $table->timestamps();
    });

    Schema::create('requerimentos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('usuario_id');
        $table->unsignedBigInteger('setor_id')->nullable();
        $table->unsignedBigInteger('assunto_requerimento_id')->nullable();
        $table->string('objetoDoRequerimento');
        $table->string('status')->nullable();
        $table->timestamps();
    });
});

it('hides completed requests by default and includes them when filtered', function () {
    $admin = Usuario::create([
        'nome' => 'Administrador',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);

    $setor = Setor::create([
        'setor_sigla' => 'CORES',
        'setor_nome' => 'Coordenação de Registros Escolares',
        'titulo' => 'Requerimentos da CORES',
    ]);

    $aluno = Usuario::create([
        'nome' => 'Ana Silva',
        'email' => 'ana@example.com',
        'password' => Hash::make('password'),
        'role' => 'aluno',
    ]);

    foreach ([
        ['Declaração', 'Aberto'],
        ['Histórico escolar', 'Concluído'],
        ['Documento despachado', 'Despacho'],
    ] as [$objeto, $status]) {
        DB::table('requerimentos')->insert([
            'usuario_id' => $aluno->id,
            'setor_id' => $setor->id,
            'objetoDoRequerimento' => $objeto,
            'status' => $status,
            'created_at' => $status === 'Despacho' ? now()->subDays(45) : now(),
            'updated_at' => $status === 'Despacho' ? now()->subDays(45) : now(),
        ]);
    }

    $padrao = $this->actingAs($admin)
        ->get(route('admin.consultar-requerimentos'));

    $padrao->assertOk()
        ->assertSee('Declaração')
        ->assertSee('Documento despachado')
        ->assertDontSee('Histórico escolar');

    $concluidos = $this->get(route('admin.consultar-requerimentos', ['status' => 'Concluído']));

    $concluidos->assertOk()
        ->assertSee('Histórico escolar')
        ->assertDontSee('Declaração')
        ->assertDontSee('Documento despachado');

    $despachados = $this->get(route('admin.consultar-requerimentos', ['status' => 'Despacho']));

    $despachados->assertOk()
        ->assertSee('Documento despachado')
        ->assertDontSee('Declaração')
        ->assertDontSee('Histórico escolar');
});
