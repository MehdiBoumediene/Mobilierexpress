<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produits;
use App\Repository\ProduitsRepository;
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

    #[Route('/détails/{id}', name: 'app_produit_details', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
        $produits = $produitsRepository->findOneBy(array('id'=>$produit));
        return $this->render('show.html.twig', [
            'produit' => $produits,
        ]);
    }
}
