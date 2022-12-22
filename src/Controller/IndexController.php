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
use App\Repository\CategoriesRepository;
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


    #[Route('Algerie/Magasin/Meuble', name: 'app_shop')]
    public function shop(ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository): Response
    {
        $produits = $produitsRepository->findAll();
        $categories = $categoriesRepository->findAll();
        return $this->render('index/shop.html.twig', [
            'categories'=> $categories,
            'produits'=> $produits
        ]);
    }

    #[Route('Algerie/Magasin/Meuble/{id}', name: 'app_produit_details', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
       
        return $this->render('index/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('Algerie/Magasin/Meuble/Commande/{id}', name: 'app_commande', methods: ['GET'])]
    public function commande(Request $request,$id,ProduitsRepository $produitsRepository): Response
    {
        $produit = $produitsRepository->findOneBy(array('id'=>$id));

        $commande = new Commandes();
        $form = $this->createForm(CommandesType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandesRepository->add($commande, true);

            return $this->redirectToRoute('app_commande', ['id'=>$id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('index/commande.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

}
