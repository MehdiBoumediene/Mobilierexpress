<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Entity\Files;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use App\Repository\FilesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
#[Route('/admin/produits')]
class ProduitsController extends AbstractController
{
    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitsRepository $produitsRepository, FilesRepository $filesRepository): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
            $files = $form->get('files')->getData();
            foreach($files as $file){
                // Je génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();

                // Je copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('videos_directory'),
                    $fichier
                );

                // Je stocke le document dans la BDD (nom du fichier)
                $file= new Files();
                $file->setPath($fichier);
            
                $produit->addFile($file);
            }
         
            $produitsRepository->add($produit, true);

         
            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_show', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produits $produit, ProduitsRepository $produitsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);


        if (null === $produit = $entityManager->getRepository(Produits::class)->find($produit)) {
            throw $this->createNotFoundException('No task found for id '.$produit);
        }

        $originalTags = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($produit->getFiles() as $tag) {
            $originalTags->add($tag);
        }

        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($originalTags as $tag) {
                if (false === $produit->getFiles()->contains($tag)) {
                    // remove the Task from the Tag
                    $tag->getProduits()->removeElement($produit);

                    // if it was a many-to-one relationship, remove the relationship like this
                   $tag->setProduit(null);

                    $entityManager->persist($tag);

                    // if you wanted to delete the Tag entirely, you can also do that
                   $entityManager->remove($tag);
                }
            }

       

            $produitsRepository->add($produit, true);

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, ProduitsRepository $produitsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitsRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
    }
}
