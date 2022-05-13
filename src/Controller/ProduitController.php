<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $repo): Response
    {
        $produits = $repo->findAll();
        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }


    #[Route('/produit', name: 'app_produit_info')]
    public function info(Produit $nom, Produit $image, Produit $prix)
    {
       
    }


    
            // --- CREATE ---
            
    
    /**
    * @Route("/newproduct/new", name="produit_create")
    */
    public function form(Request $request, EntityManagerInterface $manager, Produit $produit = null)
    {
        $produit = new Produit;
        $form = $this->createForm(ProductType::class, $produit);
        
        $form->handleRequest($request);
        
        dump($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($produit);
            $manager->flush();
            return $this->redirectToRoute('detaille', [
                'id' => $produit->getID()
            ]);
        }


        return $this->render('e_commerce/newProduct.html.twig', [
            'FormProduit' => $form->createView()
        ]);

    }
}

