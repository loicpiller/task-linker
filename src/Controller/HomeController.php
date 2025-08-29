<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ProjectRepository $projectRepo): Response
    {
        $projects = $projectRepo->findBy(['archived' => false]);

        return $this->render('home/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
