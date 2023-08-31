<?php

namespace App\Controller\Apps;

use App\Repository\AppsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'apps_')]
class AppsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AppsRepository $appsRepository): Response
    {
        return $this->render('apps/index.html.twig', [
            'apps' => $appsRepository->findBy([],['appOrder' => 'asc']),
        ]);
    }
}
