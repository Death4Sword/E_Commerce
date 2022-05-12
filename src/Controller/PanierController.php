<?php

namespace App\Controller;


use App\Repository\ProduitRepository;
use App\Service\PanierService;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(RequestStack $rs,  ProduitRepository $repo): Response
    {
        $session = $rs->getSession();
        $panier = $session->get('panier', []);
        $qt = 0;

        $panierWithData = [];

        foreach ($panier as $id => $quantite) {
            $panierWithData[] = [
                'produit' => $repo->find($id),
                'quantite' => $quantite
            ];
            $qt += $quantite;
        }

        $session->set('qt', $qt);
        $total = 0;

        foreach ($panierWithData as $item) {
            $totalItem = $item['produit']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }

        // return $this->render('panier/index.html.twig', [
        //     'controller_name' => 'PanierController',
        // ]);
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add($id, Produit $produit, RequestStack $rs)
    {
        // On récupère le panier actuel
        $session = $rs->getSession();
        $panier = $session->get('panier', []);
        $id = $produit->getId();

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute(('app_panier'));
    }

    /**
     * @Route("/panier/substract/{id}", name="panier_substract")
     */

    public function substract($id, Produit $produit, RequestStack $rs)
    {
        // On récupère le panier actuel
        $session = $rs->getSession();
        $panier = $session->get('panier', []);
        $id = $produit->getId();

        if (!empty($panier[$id])) {
            $panier[$id]--;
        } else {
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute(('app_panier'));
    }

    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */
    public function remove($id, RequestStack $rs)
    {
        $session = $rs->getSession();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id]))
            unset($panier[$id]);
        $session->set('panier', $panier);
        return $this->redirectToRoute('app_panier');
    }

    /**
     * @Route("/panier/removeAll/", name="panier_remove_all")
     */
    public function removeAll(RequestStack $rs)
    {
        $session = $rs->getSession();
        $panier = $session->get('panier', []);

        if (!empty($panier))
            unset($panier);
        $session->remove('panier');
        return $this->redirectToRoute('app_panier');
    }

    /**
     * @Route("/panier/acheter/", name="panier_achat")
     */
    public function acheter(RequestStack $rs)
    {
        $session = $rs->getSession();
        $panier = $session->get('panier', []);

        if (!empty($panier))
            unset($panier);
        $session->remove('panier');
        return $this->redirectToRoute('app_panier_achat');
    }

    /**
     * @Route("/panier/achat/", name="app_panier_achat")
     */
    public function achat()
    {
        return $this->render('panier/achat.html.twig');
    }
}
