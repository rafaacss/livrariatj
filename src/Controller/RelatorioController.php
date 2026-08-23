<?php

namespace App\Controller;

use App\Service\RelatorioAutorService;
use App\Service\RelatorioLivroService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relatorio')]
final class RelatorioController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/autor', name: 'app_relatorio_autores', methods: ['GET'])]
    public function index(RelatorioAutorService $relatorioAutorService): Response
    {
        return $this->render('relatorio/autores.html.twig', [
            'autores' => $relatorioAutorService->buscarRelatorio(),
        ]);
    }

    #[Route('/livros',name: 'app_relatorio_livros', methods: ['GET'])]
    public function livro(RelatorioLivroService $relatorioLivroService): Response
    {
        return $this->render('relatorio/livros.html.twig', [
            'livros' => $relatorioLivroService->buscarRelatorio(),
        ]);
    }
}
