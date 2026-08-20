<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class LivroController extends AbstractController
{
    #[Route('/livro', name: 'app_livro')]
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        $livros = [
            [
                'id' => 1,
                'titulo' => 'Dom Casmurro',
                'autor' => 'Machado de Assis',
            ],
            [
                'id' => 2,
                'titulo' => 'O Cortiço',
                'autor' => 'Aluísio Azevedo',
            ],
        ];

        return $this->render(
            'Livro/index.html.twig',
            compact('livros')
        );
    }
}
