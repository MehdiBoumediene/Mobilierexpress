<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes')]
class CommandesController extends AbstractController
{
    #[Route('/', name: 'app_commandes_index', methods: ['GET'])]
    public function index(CommandesRepository $commandesRepository): Response
    {
        return $this->render('commandes/index.html.twig', [
            'commandes' => $commandesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_commandes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandesRepository $commandesRepository): Response
    {
        $commande = new Commandes();
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandesRepository->add($commande, true);

            $message = (new \Swift_Message('Mobilier Express|Commande'))
        ->setFrom($form->get('Email')->getData())
        ->setTo('info@mobilierexpress-dz.com')
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'templates/emails/commande.html.twig',
                ['nom' => $form->get('nom'->getData()),
                 'Email' => $form->get('Email'->getData()),
                 'telephone' => $form->get('telephone'->getData()),
                 'adresse' => $form->get('adresse'->getData()),
                 'produit' => $form->get('produit'->getData()),
                
                ]
            ),
            'text/html'
        );

            return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commandes/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commandes_show', methods: ['GET'])]
    public function show(Commandes $commande): Response
    {
        return $this->render('commandes/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commandes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commandes $commande, CommandesRepository $commandesRepository): Response
    {
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandesRepository->add($commande, true);

            return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commandes/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commandes_delete', methods: ['POST'])]
    public function delete(Request $request, Commandes $commande, CommandesRepository $commandesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandesRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_commandes_index', [], Response::HTTP_SEE_OTHER);
    }
}
