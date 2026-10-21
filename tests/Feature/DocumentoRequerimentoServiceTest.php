<?php

use App\Models\Requerimento;
use App\Services\DocumentoRequerimentoService;
use Illuminate\Http\UploadedFile;

it('throws when an uploaded document cannot be written to storage', function () {
    $arquivo = Mockery::mock(UploadedFile::fake()->create('comprovante.pdf', 1))->makePartial();
    $arquivo->shouldReceive('storeAs')
        ->once()
        ->andReturn(false);

    expect(fn () => app(DocumentoRequerimentoService::class)->salvarArquivo(
        $arquivo,
        new Requerimento()
    ))->toThrow(RuntimeException::class, 'Não foi possível salvar o documento');
});
