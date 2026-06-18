<?php

namespace App\Http\Controllers;

use App\Services\SuapCrawler;
use App\Services\SuapWebService;

$ok = $web->login(
    'SUA_MATRICULA',
    'SUA_SENHA'
);

dd($ok);
?>