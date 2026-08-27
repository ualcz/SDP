<?php

namespace App\Services\Suap;

use Symfony\Component\DomCrawler\Crawler;

class EmailPessoalScraper
{
    /**
     * Extrai o e-mail pessoal da página do aluno no SUAP.
     */
    public function extrair(Crawler $pagina): ?string
    {
        $email = null;

        $pagina->filter('table.info tr')->each(function (Crawler $linha) use (&$email) {

            $celulas = $linha->filter('td');

            for ($i = 0; $i < $celulas->count(); $i++) {

                $texto = trim($celulas->eq($i)->text());

                if ($texto === 'E-mail Pessoal') {

                    if ($i + 1 < $celulas->count()) {
                        $valor = trim($celulas->eq($i + 1)->text());

                        if (
                            $valor !== '-' &&
                            filter_var($valor, FILTER_VALIDATE_EMAIL)
                        ) {
                            $email = $valor;
                        }
                    }

                    return;
                }
            }
        });

        return $email;
    }
}
?>