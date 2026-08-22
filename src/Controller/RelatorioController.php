<?php

namespace App\Controller;

use App\Service\RelatorioAutorService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/relatorio/autor')]
final class RelatorioController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route(name: 'app_relatorio_autores', methods: ['GET'])]
    public function index(RelatorioAutorService $relatorioAutorService): Response
    {
        return $this->render('relatorio/autores.html.twig', [
            'autores' => $relatorioAutorService->buscarRelatorio(),
        ]);
    }
}
