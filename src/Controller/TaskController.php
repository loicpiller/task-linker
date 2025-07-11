<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Task;
use App\Form\TaskTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class TaskController extends AbstractController
{
    /**
     * Retrieves a Task entity based on the 'id' GET parameter in the request.
     *
     * This method checks if the 'id' query parameter is present and corresponds
     * to an existing Task entity in the database. If the parameter is missing,
     * a BadRequestHttpException is thrown.
     *
     * @param Request                $req The current HTTP request containing the 'id' parameter.
     * @param EntityManagerInterface $em  The entity manager used to retrieve the Project.
     *
     * @return Task The Task entity corresponding to the given 'id'.
     *
     * @throws BadRequestHttpException If the 'id' parameter is missing.
     * @throws NotFoundHttpException If no Task is found for the given 'id'.
     */
    private function getTaskFromRequest(Request $req, EntityManagerInterface $em): Task
    {
        $id = $req->query->get('id');

        if (!$id) {
            throw new BadRequestHttpException("The 'id' parameter is required.");
        }

        $task = $em->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException("Task not found.");
        }

        return $task;
    }

    /**
     * Retrieves a Status entity based on the 'id' GET parameter in the request.
     *
     * This method checks if the 'id' query parameter is present and corresponds
     * to an existing Status entity in the database. If the parameter is missing,
     * a BadRequestHttpException is thrown.
     *
     * @param Request                $req The current HTTP request containing the 'id' parameter.
     * @param EntityManagerInterface $em  The entity manager used to retrieve the Project.
     *
     * @return Status The Status entity corresponding to the given 'id'.
     *
     * @throws BadRequestHttpException If the 'id' parameter is missing.
     * @throws NotFoundHttpException If no Status is found for the given 'id'.
     */
    private function getStatusFromRequest(Request $req, EntityManagerInterface $em): Status
    {
        $id = $req->query->get('status_id');

        if (!$id) {
            throw new BadRequestHttpException("The 'status_id' parameter is required.");
        }

        $task = $em->getRepository(Status::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException("Status not found.");
        }

        return $task;
    }

    #[Route('/task', name: 'task_details')]
    public function index(Request $req, EntityManagerInterface $em): Response
    {
        $task = $this->getTaskFromRequest($req, $em);
        $form = $this->createForm(TaskTypeForm::class, $task);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => true,
            'task' => $task,
        ]);
    }

    #[Route('/task/new', name: 'task_new')]
    public function new(Request $req, EntityManagerInterface $em): Response
    {

        $status = $this->getStatusFromRequest($req, $em);
        $project = $status->getProject();

        $task = new Task();
        $task->setStatus($status);
        $task->setProject($project);

        $form = $this->createForm(TaskTypeForm::class, $task);

        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('project_details', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('task/form.html.twig', [
            'form' => $form->createView(),
            'isEdit' => false,
            'task' => $task,
        ]);
    }
}
