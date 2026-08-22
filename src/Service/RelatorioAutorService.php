<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class RelatorioAutorService
{
    public function __construct(private Connection $connection) {

    }

    /**
     * @throws Exception
     */
    public function buscarRelatorio(): array
    {
        $linhas = $this->connection->fetchAllAssociative(
            "SELECT
                CodAu,
                Nome,
                Codl,
                Titulo,
                Editora,
                Edicao,
                AnoPublicacao,
                Valor,
                Assuntos
             FROM vw_relatorio_autor
             ORDER BY Nome, Titulo"
        );

        return $this->agruparPorAutor($linhas);
    }

    private function agruparPorAutor(array $linhas): array
    {
        $autores = [];

        foreach ($linhas as $linha) {
            $codAutor = (int) $linha['CodAu'];

            if (!isset($autores[$codAutor])) {
                   $autores[$codAutor] = [
                       'Nome' => $linha['Nome'],
                       'livros' => [],
                       'total' => [],
                   ];
            }

            $autores[$codAutor]['livros'][] = [
                'Codigo'          => $linha['Codl'],
                'Titulo'        => $linha['Titulo'],
                'Editora'       => $linha['Editora'],
                'Edicao'        => $linha['Edicao'],
                'AnoPublicacao' => $linha['AnoPublicacao'],
                'Valor'         => $linha['Valor'],
                'Assuntos'      => $linha['Assuntos'] ? explode(', ', $linha['Assuntos']) : '',
            ];
        }
        return array_values($autores);
    }
}
