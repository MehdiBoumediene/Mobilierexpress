<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Produits;
use App\Entity\Commandes;
use App\Repository\ProduitsRepository;
use Symfony\Component\Mailer\MailerInterface; 
use Symfony\Component\Mime\BodyRendererInterface;
use App\Repository\CategoriesRepository;
use App\Form\FiltreProduitsType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        $produits = $produitsRepository->findAll();

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'produits'=> $produits
        ]);
    }


    #[Route('algerie/magasin/meuble', name: 'app_shop', methods: ['GET', 'POST'])]
    public function shop(Request $request,ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository): Response
    {
        $produits = $produitsRepository->findAll();
        $categories = $categoriesRepository->findAll();

        $form = $this->createForm(FiltreProduitsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     
        $categorie = $form->get('categorie')->getData();
        

       
        $produits =  $produitsRepository->findBy(array('categorie'=>$categorie));



            return $this->renderForm('index/shop.html.twig', [
                'produits' => $produits,
                'categories'=> $categories,
                'form' => $form,
            ]);
        }

        return $this->renderForm('index/shop.html.twig', [
            'categories'=> $categories,
            'produits'=> $produits,
            'form' => $form,
        ]);
    }

    #[Route('algerie/magasin/meuble/{id}', name: 'app_produit_details', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
       
        return $this->render('index/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('algérie/magasin/meuble/commande/{id}', name: 'app_commande', methods: ['GET','POST'])]
    public function commande(MailerInterface $mailer, Request $request,$id,ProduitsRepository $produitsRepository,CommandesRepository $commandesRepository): Response
    {
        $produit = $produitsRepository->findOneBy(array('id'=>$id));

        $commande = new Commandes();
        $form = $this->createForm(CommandesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandesRepository->add($commande, true);

            $email = (new TemplatedEmail())
                ->from($form->get('Email')->getData())
                ->to('info@mobilierexpress-dz.com','elm3hdi@gmail.com')
                ->subject('Nouvelle commande')

                // path of the Twig template to render
                ->htmlTemplate('emails/commande.html.twig')

                // pass variables (name => value) to the template
                ->context([
                    'nom' => $form->get('nom')->getData(),
                 'Email' => $form->get('Email')->getData(),
                 'telephone' => $form->get('telephone')->getData(),
                 'adresse' => $form->get('adresse')->getData(),
                 'produit' => $form->get('produit')->getData(),
                ])
                
            ;
       
            $mailer->send($email);

            return $this->redirectToRoute('app_commande', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('index/commande.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

}
