<?php
declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\RelatorioAutorService;
use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Connection;

final class RelatorioAutorServiceTest extends TestCase
{
    public function testLivroComTresAutoresApareceEmTresGrupos(): void
    {
        $service = new RelatorioAutorService(
            $this->createStub(Connection::class)
        );

        $linhas = [
            [
                'CodAu' => 1,
                'Nome' => 'Autor 1',
                'Codl' => '100',
                'Titulo' => 'Livro compartilhado',
                'Editora' => 'Editora A',
                'Edicao' => 1,
                'AnoPublicacao' => '2024',
                'Valor' => '59.90',
                'Assuntos' => 'PHP, Symfony',
            ],
            [
                'CodAu' => 2,
                'Nome' => 'Autor 2',
                'Codl' => '100',
                'Titulo' => 'Livro compartilhado',
                'Editora' => 'Editora A',
                'Edicao' => 1,
                'AnoPublicacao' => '2024',
                'Valor' => '59.90',
                'Assuntos' => 'PHP, Symfony',
            ],
            [
                'CodAu' => 3,
                'Nome' => 'Autor 3',
                'Codl' => '100',
                'Titulo' => 'Livro compartilhado',
                'Editora' => 'Editora A',
                'Edicao' => 1,
                'AnoPublicacao' => '2024',
                'Valor' => '59.90',
                'Assuntos' => 'PHP, Symfony',
            ],
        ];

        $resultado = $service->agruparPorAutor($linhas);

        self::assertCount(3, $resultado);

        self::assertSame('Autor 1', $resultado[0]['Nome']);
        self::assertSame('Autor 2', $resultado[1]['Nome']);
        self::assertSame('Autor 3', $resultado[2]['Nome']);

        foreach ($resultado as $grupo) {
            self::assertCount(1, $grupo['livros']);
            self::assertSame('100', $grupo['livros'][0]['Codigo']);
            self::assertSame('Livro compartilhado', $grupo['livros'][0]['Titulo']);
        }
    }

    public function testAutorComDoisLivros(): void
    {
        $service = new RelatorioAutorService(
            $this->createStub(Connection::class)
        );

        $linhas = [
            [
                'CodAu' => 1,
                'Nome' => 'Autor 1',
                'Codl' => '100',
                'Titulo' => 'Livro compartilhado 1',
                'Editora' => 'Editora A',
                'Edicao' => 1,
                'AnoPublicacao' => '2024',
                'Valor' => '59.90',
                'Assuntos' => 'PHP, Symfony',
            ],
            [
                'CodAu' => 1,
                'Nome' => 'Autor 1',
                'Codl' => '200',
                'Titulo' => 'Livro compartilhado 2',
                'Editora' => 'Editora A',
                'Edicao' => 1,
                'AnoPublicacao' => '2024',
                'Valor' => '59.90',
                'Assuntos' => 'PHP, Symfony',
            ],
        ];

        $resultado = $service->agruparPorAutor($linhas);

        // Um autor deve gerar apenas um grupo.
        self::assertCount(1, $resultado);

        self::assertSame('Autor 1', $resultado[0]['Nome']);

        // O grupo do autor deve conter os dois livros.
        self::assertCount(2, $resultado[0]['livros']);

        self::assertSame('100', $resultado[0]['livros'][0]['Codigo']);
        self::assertSame('Livro compartilhado 1', $resultado[0]['livros'][0]['Titulo']);

        self::assertSame('200', $resultado[0]['livros'][1]['Codigo']);
        self::assertSame('Livro compartilhado 2', $resultado[0]['livros'][1]['Titulo']);
    }
}
