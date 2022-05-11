<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ECommerceController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('e_commerce/home.html.twig');
    }

    #[Route('/e/commerce', name: 'app_e_commerce')]
    public function index(): Response
    {
        return $this->render('e_commerce/index.html.twig', [
            'controller_name' => 'ECommerceController',
        ]);
    }


    /**
     * @Route("/blog/contact", name="blog_contact")
     */
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $manager, ContactNotification $notification)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $contact->setCreatedAt(new \DateTimeInterface);
            $notification->notify($contact);
            $this->addFlash('success', 'Votre Email a bien été envoyé');
            $manager->persist($contact); // on prépare l'insertion
            $manager->flush(); // on execute l'insertion
        }

        return $this->render("e_commerce/contact.html.twig", [
            'formContact' => $form->createView()
        ]);
    }
}
