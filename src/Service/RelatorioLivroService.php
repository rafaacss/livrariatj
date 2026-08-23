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
}
