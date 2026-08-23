<?php

namespace App\Controller;

use App\Service\RelatorioAutorService;
use App\Service\RelatorioLivroService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

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

    /**
     * @throws Exception
     */
    #[Route('/livros/pdf',name: 'app_relatorio_pdf_livros', methods: ['GET'])]
    public function livroPdf(RelatorioLivroService $relatorioLivroService): Response
    {
        $html = $this->renderView('relatorio/livros.pdf.html.twig', [
            'livros' => $relatorioLivroService->buscarRelatorio(),
        ]);

        return new Response($this->gerarPdf($html)->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="relatorio.pdf"',
        ]);
    }

    #[Route('/autor/pdf',name: 'app_relatorio_pdf_autores', methods: ['GET'])]
    public function autorPdf(RelatorioAutorService $relatorioAutorService): Response
    {
        $html = $this->renderView('relatorio/autores.pdf.html.twig', [
            'autores' => $relatorioAutorService->buscarRelatorio(),
        ]);

        return new Response($this->gerarPdf($html)->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="relatorio.pdf"',
        ]);
    }

    private function gerarPdf ($html): Dompdf
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($pdfOptions);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('relatorio.pdf');

        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf;

    }
}
