<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $instaImages = [
            'insta-post-1.png',
            'insta-post-1.png',
            'insta-post-1.png',
            'insta-post-1.png',
            'insta-post-1.png',
            'insta-post-1.png',
            'insta-post-1.png',
        ];

        return $this->render('pages/home.html.twig', [
            'instaImages' => $instaImages,
        ]);
    }

    #[Route('/debarrassage', name: 'debarrassage')]
    public function debarrassage(): Response
    {
        return $this->render('pages/debarrassage.html.twig');
    }

    #[Route('/brocante', name: 'brocante')]
    public function brocante(): Response
    {
        return $this->render('pages/brocante.html.twig');
    }

    #[Route('/surcyclage', name: 'surcyclage')]
    public function surcyclage(): Response
    {
        return $this->render('pages/surcyclage.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('pages/contact.html.twig');
    }
}
