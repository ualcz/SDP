<?php

use App\Models\Usuario;
use Carbon\Carbon;

it('considers the student a minor only before the eighteenth birthday', function () {
    Carbon::setTestNow('2026-10-29 12:00:00');

    try {
        expect((new Usuario(['data_nascimento' => '2008-10-30']))->isMenorDeIdade())->toBeTrue()
            ->and((new Usuario(['data_nascimento' => '2008-10-29']))->isMenorDeIdade())->toBeFalse()
            ->and((new Usuario())->isMenorDeIdade())->toBeFalse();
    } finally {
        Carbon::setTestNow();
    }
});
