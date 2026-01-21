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
}
