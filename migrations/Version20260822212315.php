<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260822212315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria view do relatório de livros agrupados por autor';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE OR REPLACE VIEW vw_relatorio_autor AS
            SELECT
                a.CodAu,
                a.Nome,
                l.Codl,
                l.Titulo,
                l.Editora,
                l.Edicao,
                l.AnoPublicacao,
                l.Valor,
                GROUP_CONCAT(
                    DISTINCT s.Descricao
                    ORDER BY s.Descricao
                    SEPARATOR ', '
                ) AS Assuntos
            FROM Autor a
            INNER JOIN Livro_Autor la
                ON la.Autor_CodAu = a.CodAu
            INNER JOIN Livro l
                ON l.Codl = la.Livro_Codl
            LEFT JOIN Livro_Assunto ls
                ON ls.Livro_Codl = l.Codl
            LEFT JOIN Assunto s
                ON s.codAs = ls.Assunto_codAs
            GROUP BY
                a.CodAu,
                a.Nome,
                l.Codl,
                l.Titulo,
                l.Editora,
                l.Edicao,
                l.AnoPublicacao,
                l.Valor
            ORDER BY
                a.Nome,
                l.Titulo
        ");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW IF EXISTS vw_relatorio_autor');

    }
}
