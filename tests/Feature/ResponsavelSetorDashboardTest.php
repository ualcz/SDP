<?php

use App\Models\Requerimento;
use App\Models\Setor;
use App\Models\Usuario;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('usuarios', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('turma_codigo')->nullable();
        $table->string('role')->default('aluno');
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

    Schema::create('requerimentos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('usuario_id');
        $table->unsignedBigInteger('setor_id')->nullable();
        $table->string('objetoDoRequerimento');
        $table->text('motivo')->nullable();
        $table->string('status')->nullable();
        $table->timestamps();
    });

    Schema::create('historico_requerimentos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('requerimento_id');
        $table->unsignedBigInteger('user_id')->nullable();
        $table->string('status');
        $table->text('observacao')->nullable();
        $table->boolean('solicita_novo_documento')->default(false);
        $table->string('nome_documento_solicitado')->nullable();
        $table->timestamps();
    });
});

it('renders the sector dashboard with filtered and paginated requests', function () {
    $responsavel = Usuario::create([
        'nome' => 'Responsável',
        'email' => 'responsavel@example.com',
        'password' => Hash::make('password'),
        'role' => 'responsavel',
    ]);

    $setor = Setor::create([
        'setor_sigla' => 'CORES',
        'setor_nome' => 'Coordenação de Registros Escolares',
        'titulo' => 'Requerimentos da CORES',
    ]);
    $responsavel->setoresSobResponsabilidade()->attach($setor->id);

    $aluna = Usuario::create([
        'nome' => 'Ana Silva',
        'email' => 'ana@example.com',
        'password' => Hash::make('password'),
        'turma_codigo' => '2026.1.INFO',
        'role' => 'aluno',
    ]);

    Requerimento::create([
        'usuario_id' => $aluna->id,
        'setor_id' => $setor->id,
        'objetoDoRequerimento' => 'Declaração',
        'motivo' => 'Solicitação de declaração',
        'status' => 'Aberto',
    ]);

    Requerimento::create([
        'usuario_id' => $aluna->id,
        'setor_id' => $setor->id,
        'objetoDoRequerimento' => 'Histórico escolar',
        'motivo' => 'Solicitação concluída',
        'status' => 'Concluído',
    ]);

    Requerimento::create([
        'usuario_id' => $aluna->id,
        'setor_id' => $setor->id,
        'objetoDoRequerimento' => 'Protocolo encerrado',
        'motivo' => 'Solicitação encerrada',
        'status' => 'Encerrado',
    ]);

    $responsePadrao = $this->actingAs($responsavel)
        ->get(route('setor.responsavel.dashboard', $setor->id));

    $responsePadrao->assertOk()
        ->assertSee('Declaração')
        ->assertDontSee('Histórico escolar')
        ->assertDontSee('Protocolo encerrado');

    $response = $this->actingAs($responsavel)
        ->get(route('setor.responsavel.dashboard', $setor->id) . '?aluno=Ana&turma=2026.1&status=Aberto');

    $response->assertOk()
        ->assertSee('Requerimentos do setor')
        ->assertSee('Ana Silva')
        ->assertSee('Declaração')
        ->assertDontSee('Histórico escolar');

    $responseConcluidos = $this->get(route('setor.responsavel.dashboard', $setor->id) . '?status=Concluído');

    $responseConcluidos->assertOk()
        ->assertSee('Histórico escolar')
        ->assertDontSee('Declaração')
        ->assertDontSee('Protocolo encerrado');

    $responseEncerrados = $this->get(route('setor.responsavel.dashboard', $setor->id) . '?status=Encerrado');

    $responseEncerrados->assertOk()
        ->assertSee('Protocolo encerrado')
        ->assertDontSee('Declaração');
});
