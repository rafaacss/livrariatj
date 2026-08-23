<?php

declare(strict_types=1);

namespace App\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final class RelatorioLivroService
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
                Titulo,
                Editora,
                Edicao,
                AnoPublicacao,
                Valor,
                Assuntos,
                autores
             FROM vw_relatorio_livro
             ORDER BY Titulo"
        );

        return array_values($linhas);
    }

    private function agrupar(array $linhas): array
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
