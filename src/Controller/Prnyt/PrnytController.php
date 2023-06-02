<?php

namespace App\Controller\Prnyt;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/prnyt/prnyt', name: 'prnyt_')]
class PrnytController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('prnyt/prnyt/index.html.twig', [
            'controller_name' => 'PrnytController',
        ]);
    }
}
