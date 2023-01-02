<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommandesType;
use App\Repository\CommandesRepository;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(ProduitsRepository $produitsRepository,NewsRepository $newsRepository): Response
    {
        $produits = $produitsRepository->findBy(array('accueil'=>true),array('id' => 'DESC'));
        $news = $newsRepository->findAll();
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'produits'=> $produits,
            'news'=> $news
        ]);
    }


    #[Route('algerie/magasin/meuble', name: 'app_shop', methods: ['GET', 'POST'])]
    public function shop(Request $request,ProduitsRepository $produitsRepository,CategoriesRepository $categoriesRepository, PaginatorInterface $paginator): Response
    {
        $produits = $produitsRepository->findAll();
        $categories = $categoriesRepository->findAll();

        $form = $this->createForm(FiltreProduitsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     
        $categorie = $form->get('categorie')->getData();
        

       if($categorie){
        $produits =  $produitsRepository->findBy(array('categorie'=>$categorie));
       }else{
        $produits =  $produitsRepository->findAll();
       }
        

        $produits = $paginator->paginate(
            $produits, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

            return $this->renderForm('index/shop.html.twig', [
                'produits' => $produits,
                'categories'=> $categories,
                'form' => $form,
            ]);
        }

        $produits = $paginator->paginate(
            $produits, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            10 // Nombre de résultats par page
        );

        return $this->renderForm('index/shop.html.twig', [
            'categories'=> $categories,
            'produits'=> $produits,
            'form' => $form,
        ]);
    }

    #[Route('algerie/magasin/meuble/{categorie}/{id}', name: 'app_produit_details', methods: ['GET'])]
    public function show($categorie,$id,ProduitsRepository $produitsRepository): Response
    {
        $produit = $produitsRepository->findOneBy(array('nom'=>$id));
        return $this->render('index/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('algérie/magasin/meuble/commande/{categorie}-{id}', name: 'app_commande', methods: ['GET','POST'])]
    public function commande(MailerInterface $mailer, Request $request,$id,ProduitsRepository $produitsRepository,CommandesRepository $commandesRepository): Response
    {
        $produit = $produitsRepository->findOneBy(array('nom'=>$id));

        $commande = new Commandes();
        $form = $this->createForm(CommandesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $commande->setNom($form->get('nom')->getData());
            $commande->setTelephone($form->get('telephone')->getData());
            $commande->setEmail($form->get('Email')->getData());
            $commande->setAdresse($form->get('adresse')->getData());
            $commande->setProduit($form->get('produit')->getData());
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

    #[Route('mobilierexpress', name: 'app_about', methods: ['GET'])]
    public function apropos(): Response
    {
       
        return $this->render('index/apropos.html.twig', [
           
        ]);
    }

}
