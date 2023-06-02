<?php

namespace App\Controller\Recpt;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recpt/recpt', name: 'recpt_')]
class RecptController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('recpt/recpt/index.html.twig', [
            'controller_name' => 'RecptController',
        ]);
    }
}
