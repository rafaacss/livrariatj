<?php
declare(strict_types=1);
namespace App\DataFixtures;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\VarDumper\VarDumper;


class LivrariaFixtures extends Fixture
{
    private function getLivros(): array
    {
        return [
            1 => [
                'titulo' => 'Curso de Direito Constitucional',
                'editora' => 'Saraiva',
                'edicao' => 18,
                'anoPublicacao' => '2023',
                'valor' => '133.90',
                'autores' => [4],
                'assunto' => [0],
            ],
            2 => [
                'titulo' => 'Curso de Processo Penal',
                'editora' => 'Atlas',
                'edicao' => 26,
                'anoPublicacao' => '2022',
                'valor' => '133.90',
                'autores' => [0,1,2],
                'assunto' => [1],
            ],
            3 => [
                'titulo' => 'Direito Administrativo Descomplicado',
                'editora' => 'Saraiva',
                'edicao' => 18,
                'anoPublicacao' => '2024',
                'valor' => '133.90',
                'autores' => [3],
                'assunto' => [],
            ],

        ];
    }

    private function getAutores(): array
    {
        return [
            0 =>'Alexandre de Moraes',
            1 =>'Flávio Tartuce',
            2 =>'Marcelo Alexandrino',
            3 =>'Nestor Távora',
            4 =>'Vicente Paulo',
            5 => 'Maria Helena Diniz'
        ];
    }

    private function getAssuntos(): array
    {
        return [
            0 => 'Direito Civil',
            1 => 'Direito Penal',
            2 => 'Processo Civil',
            3 => 'Direito Constituc.',
            4 => 'Direito Tributário',
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $autores = [];
        $assuntos = [];

        foreach ($this->getAutores() as $indice => $autor) {
            $novoautor = new Autor();
            $novoautor->setNome($autor);

            $manager->persist($novoautor);
            $autores[$indice] = $novoautor;
        }
        echo "1 - autores ok\n";

        foreach ($this->getAssuntos() as $indice => $assunto) {
            $novoassunto = new Assunto();
            $novoassunto->setDescricao($assunto);

            $manager->persist($novoassunto);
            $assuntos[$indice] = $novoassunto;
        }
        echo "1 - assuntos ok\n";

        foreach ($this->getLivros() as $i=>$livro) {
            $novolivro = new Livro();
            $novolivro->setTitulo($livro['titulo'] );
            $novolivro->setEditora($livro['editora'] );
            $novolivro->setEdicao($livro['edicao'] );
            $novolivro->setAnoPublicacao($livro['anoPublicacao'] );
            $novolivro->setValor($livro['valor'] );

            foreach ($livro['autores'] as $autor) {
                $novolivro->addAutor($autores[$autor]);
            }

            foreach ($livro['assunto'] as $assunto){
                $novolivro->addAssunto($assuntos[$assunto]);
            }

            $manager->persist($novolivro);
        }
        echo "1 - livros ok\n";

        $manager->flush();
        echo "4 - flush ok\n";

    }


}
