<?php

namespace App\Controller\Ctrqa;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ctrqa', name: 'ctrqa_')]
class CtrqaController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('ctrqa/ctrqa/index.html.twig', [
            'controller_name' => 'CtrqaController',
        ]);
    }
}
