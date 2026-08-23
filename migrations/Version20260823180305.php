<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260823180305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Cria view do relatório agrupados por livro';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE VIEW vw_relatorio_livro AS
            SELECT
                Livro.Codl
                GROUP_CONCAT(DISTINCT Autor.Nome ORDER BY Autor.Nome ASC SEPARATOR ', ') AS autores,
                Livro.Titulo,
                Livro.Editora,
                Livro.Edicao,
                Livro.AnoPublicacao,
                Livro.Valor,
                GROUP_CONCAT(DISTINCT Assunto.Descricao ORDER BY Assunto.Descricao ASC SEPARATOR ', ') AS assuntos
            FROM Livro
                     JOIN Livro_Autor ON Livro.Codl = Livro_Autor.Livro_Codl
                     JOIN Autor ON Autor.CodAu = Livro_Autor.Autor_CodAu
                     LEFT JOIN Livro_Assunto ON Livro.Codl = Livro_Assunto.Livro_Codl
                     LEFT JOIN Assunto ON Assunto.CodAs = Livro_Assunto.Assunto_CodAs
            GROUP BY Livro.Codl,
                     Livro.Titulo,
                     Livro.Editora,
                     Livro.Edicao,
                     Livro.AnoPublicacao,
                     Livro.Valor;
        ");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP VIEW IF EXISTS vw_relatorio_livro');
    }
}
