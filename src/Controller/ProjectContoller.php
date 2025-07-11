<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class ProjectContoller extends AbstractController
{
    /**
     * Retrieves a Project entity based on the 'id' GET parameter in the request.
     *
     * This method checks if the 'id' query parameter is present and corresponds
     * to an existing Project entity in the database. If the parameter is missing,
     * a BadRequestHttpException is thrown.
     *
     * @param Request                $req The current HTTP request containing the 'id' parameter.
     * @param EntityManagerInterface $em  The entity manager used to retrieve the Project.
     *
     * @return Project The Project entity corresponding to the given 'id'.
     *
     * @throws BadRequestHttpException If the 'id' parameter is missing.
     * @throws NotFoundHttpException If no Project is found for the given 'id'.
     */
    private function getProjectFromRequest(Request $req, EntityManagerInterface $em): Project
    {
        $id = $req->query->get('id');

        if (!$id) {
            throw new BadRequestHttpException("The 'id' parameter is required.");
        }

        $project = $em->getRepository(Project::class)->find($id);

        if (!$project) {
            throw $this->createNotFoundException("Project not found.");
        }

        return $project;
    }

    #[Route('/project', name: 'project_details')]
    public function index(Request $req, EntityManagerInterface $em): Response
    {
        $project = $this->getProjectFromRequest($req, $em);

        return $this->render('project/details.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/project/new', name: 'project_new')]
    public function new(Request $req, EntityManagerInterface $em): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectTypeForm::class, $project);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => false,
        ]);
    }

    #[Route('/project/edit', name: 'project_edit')]
    public function edit(Request $req, EntityManagerInterface $em): Response
    {
        $project = $this->getProjectFromRequest($req, $em);

        $form = $this->createForm(ProjectTypeForm::class, $project);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => true,
            'project' => $project,
        ]);
    }
}
