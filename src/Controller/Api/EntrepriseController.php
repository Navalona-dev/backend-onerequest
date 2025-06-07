<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EntrepriseController extends AbstractController
{
    #[Route('/api/entreprise', name: 'app_api_entreprise')]
    public function index(): Response
    {
        return $this->render('api/entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
        ]);
    }
}
