<?php

use App\Models\DocumentoAssunto;
use App\Models\Endereco;
use App\Models\Usuario;
use App\Services\ModeloDocumentoService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

it('generates a personalized PDF from the registered DOCX model', function () {
    Storage::fake('local');

    $word = new PhpWord();
    $word->addSection()->addText('{{ nome }} - {{ matricula }}');
    $modeloTemporario = tempnam(sys_get_temp_dir(), 'modelo-pdf-teste-');
    IOFactory::createWriter($word, 'Word2007')->save($modeloTemporario);
    Storage::disk('local')->put('modelos/modelo.docx', file_get_contents($modeloTemporario));
    unlink($modeloTemporario);

    $documento = new DocumentoAssunto([
        'nome' => 'Declaracao',
        'arquivo_modelo' => 'modelos/modelo.docx',
    ]);
    $aluno = new Usuario([
        'nome' => 'Ana Maria',
        'matricula' => '20260001',
    ]);
    $aluno->setRelation('endereco', new Endereco());

    $pdf = app(ModeloDocumentoService::class)->gerarPdfParaAluno($documento, $aluno);

    expect(file_get_contents($pdf))->toStartWith('%PDF-')
        ->and(filesize($pdf))->toBeGreaterThan(500);

    unlink($pdf);
});

it('adds one legal guardian signature space to models generated for minors', function () {
    Storage::fake('local');
    \Carbon\Carbon::setTestNow('2026-10-29 12:00:00');

    $word = new PhpWord();
    $word->addSection()->addText('Termo do estudante');
    $modeloTemporario = tempnam(sys_get_temp_dir(), 'modelo-menor-teste-');
    IOFactory::createWriter($word, 'Word2007')->save($modeloTemporario);
    Storage::disk('local')->put('modelos/modelo-menor.docx', file_get_contents($modeloTemporario));
    unlink($modeloTemporario);

    $documento = new DocumentoAssunto([
        'nome' => 'Termo',
        'arquivo_modelo' => 'modelos/modelo-menor.docx',
    ]);
    $aluno = new Usuario([
        'nome' => 'Ana Maria',
        'data_nascimento' => '2010-06-30',
    ]);

    $docx = app(ModeloDocumentoService::class)->gerarParaAluno($documento, $aluno);
    $arquivo = new ZipArchive();
    expect($arquivo->open($docx))->toBeTrue();
    $conteudo = $arquivo->getFromName('word/document.xml');
    $arquivo->close();
    unlink($docx);
    \Carbon\Carbon::setTestNow();

    expect($conteudo)
        ->toContain('ASSINATURA DO RESPONSÁVEL LEGAL')
        ->toContain('Responsável legal')
        ->not->toContain('Pai / Responsável legal')
        ->not->toContain('Mãe / Responsável legal');
});
