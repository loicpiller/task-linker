<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Home page controller.
 */
final class HomeController extends AbstractController
{
    /**
     * Display the home page with active projects.
     *
     * @param ProjectRepository $projectRepo
     *
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(ProjectRepository $projectRepo): Response
    {
        $projects = $projectRepo->findBy(['archived' => false]);

        return $this->render('home/index.html.twig', [
            'projects' => $projects,
        ]);
    }
}
