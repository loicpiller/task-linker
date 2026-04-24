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

/**
 * Controller for task management.
 */
final class TaskController extends AbstractController
{
    /**
     * Display and handle task edition.
     *
     * @param Request                $req
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
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

    /**
     * Delete a task.
     *
     * @param Request                $req
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    #[Route('/task/delete', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $req, EntityManagerInterface $em): Response
    {
        $task = $this->getTaskFromRequest($req, $em);
        $projectId = $task->getProject()->getId();

        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('project_details', ['id' => $projectId]);
    }

    /**
     * Create a new task.
     *
     * @param Request                $req
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
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

    /**
     * Retrieves a Task entity based on the 'id' GET parameter in the request.
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
     * Retrieves a Status entity based on the 'status_id' GET parameter in the request.
     *
     * @param Request                $req The current HTTP request containing the 'status_id' parameter.
     * @param EntityManagerInterface $em  The entity manager used to retrieve the Status.
     *
     * @return Status The Status entity corresponding to the given 'status_id'.
     *
     * @throws BadRequestHttpException If the 'status_id' parameter is missing.
     * @throws NotFoundHttpException If no Status is found for the given 'status_id'.
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
}
