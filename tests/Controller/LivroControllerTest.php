<?php

namespace App\Tests\Controller;

use App\Entity\Livro;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class LivroControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;

    /** @var EntityRepository<Livro> */
    private EntityRepository $livroRepository;
    private string $path = '/livro/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->livroRepository = $this->manager->getRepository(Livro::class);

        foreach ($this->livroRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livros');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Salvar', [
            'livro[Titulo]' => 'Titulo',
            'livro[Editora]' => 'Editora',
            'livro[Edicao]' => 40,
            'livro[AnoPublicacao]' => '2030',
            'livro[Valor]' => '111.11',
        ]);

        self::assertResponseRedirects('/livro');

        self::assertSame(1, $this->livroRepository->count([]));

    }

    public function testShow(): void
    {
        $fixture = new Livro();
        $fixture->setTitulo('Titulo do meu livro');
        $fixture->setEditora('Minha Editora');
        $fixture->setEdicao(28);
        $fixture->setAnoPublicacao('2023');
        $fixture->setValor('111.15');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getCodl()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Livro');

    }

    public function testEdit(): void
    {
        $fixture = new Livro();
        $fixture->setTitulo('Value');
        $fixture->setEditora('Value');
        $fixture->setEdicao(28);
        $fixture->setAnoPublicacao('2023');
        $fixture->setValor('111.52');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getCodl()));

        $this->client->submitForm('Update', [
            'livro[Titulo]' => 'Novo livro',
            'livro[Editora]' => 'Nova editora',
            'livro[Edicao]' => 30,
            'livro[AnoPublicacao]' => '2030',
            'livro[Valor]' => '222.20',
        ]);

        self::assertResponseRedirects('/livro');

        $fixture = $this->livroRepository->findAll();

        self::assertSame('Novo livro', $fixture[0]->getTitulo());
        self::assertSame('Nova editora', $fixture[0]->getEditora());
        self::assertSame(30, $fixture[0]->getEdicao());
        self::assertSame('2030', $fixture[0]->getAnoPublicacao());
        self::assertSame('222.20', $fixture[0]->getValor());

    }

    public function testRemove(): void
    {
        $fixture = new Livro();
        $fixture->setTitulo('Value');
        $fixture->setEditora('Value');
        $fixture->setEdicao(50);
        $fixture->setAnoPublicacao('2025');
        $fixture->setValor('8888.88');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getCodl()));
        $this->client->submitForm('Excluir');

        self::assertResponseRedirects('/livro');
        self::assertSame(0, $this->livroRepository->count([]));

    }
}
