<?php

namespace App\Controller;

use App\Entity\Assunto;
use App\Form\AssuntoType;
use App\Repository\AssuntoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assunto')]
final class AssuntoController extends AbstractController
{
    #[Route(name: 'app_assunto_index', methods: ['GET'])]
    public function index(AssuntoRepository $assuntoRepository): Response
    {
        return $this->render('assunto/index.html.twig', [
            'assuntos' => $assuntoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_assunto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $assunto = new Assunto();
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($assunto);
            $entityManager->flush();

            return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('assunto/new.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
        ]);
    }

    #[Route('/{codAs}', name: 'app_assunto_show', methods: ['GET'])]
    public function show(#[MapEntity(id: 'codAs')] Assunto $assunto): Response
    {
        return $this->render('assunto/show.html.twig', [
            'assunto' => $assunto,
        ]);
    }

    #[Route('/{codAs}/edit', name: 'app_assunto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, #[MapEntity(id: 'codAs')] Assunto $assunto, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssuntoType::class, $assunto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('assunto/edit.html.twig', [
            'assunto' => $assunto,
            'form' => $form,
        ]);
    }

    #[Route('/{codAs}', name: 'app_assunto_delete', methods: ['POST'])]
    public function delete(Request $request, #[MapEntity(id: 'codAs')] Assunto $assunto, EntityManagerInterface $entityManager): Response
    {
        try {
            if($assunto->getLivros()->count() > 0) {
                $this->addFlash(
                    'danger',
                    'Erro ao excluir assunto! Existe livro vinculado a este assunto.'
                );
                return $this->redirectToRoute('app_autor_index', [], Response::HTTP_SEE_OTHER);
            }

            if ($this->isCsrfTokenValid('delete'.$assunto->getCodAs(), $request->getPayload()->getString('_token'))) {
                $entityManager->remove($assunto);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Autor cadastrado com sucesso!'
                );
            }

            $this->addFlash(
                'danger',
                'Erro ao exluir assunto!, se o erro persistir, entre em contato com o suporte.'
            );
        }catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $exception) {
            $this->addFlash(
                'danger',
                'Erro ao exluir assunto! Existe livro vinculado a este assunto.'
            );
        }


        return $this->redirectToRoute('app_assunto_index', [], Response::HTTP_SEE_OTHER);
    }
}
