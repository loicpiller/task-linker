<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use App\Form\EmployeeTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Controller for employee management.
 */
final class EmployeeController extends AbstractController
{
    /**
     * Display the team list.
     *
     * @param EmployeeRepository $employeeRepo
     *
     * @return Response
     */
    #[Route('/team', name: 'team')]
    public function index(EmployeeRepository $employeeRepo): Response
    {
        $employees = $employeeRepo->findBy(['active' => true]);

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

    /**
     * Edit an employee.
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    #[Route('/employee/edit', name: 'employee_edit')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $employee = $this->getEmployeeFromRequest($request, $em);

        $form = $this->createForm(EmployeeTypeForm::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('team');
        }

        return $this->render('employee/edit.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }

    /**
     * Soft-delete an employee by deactivating them.
     *
     * @param int                    $id
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse
     */
    #[Route('/employee/delete/{id}', name: 'employee_delete')]
    public function delete(int $id, EntityManagerInterface $em): RedirectResponse
    {
        $employee = $em->getRepository(Employee::class)->find($id);

        if (!$employee) {
            throw new NotFoundHttpException("Employé not found.");
        }

        $employee->setActive(false);
        $em->flush();

        return $this->redirectToRoute('team');
    }

    /**
     * Retrieves an Employee entity based on the 'id' GET parameter in the request.
     *
     * @param Request                $request The current HTTP request.
     * @param EntityManagerInterface $em      The entity manager.
     *
     * @return Employee The Employee entity.
     *
     * @throws BadRequestHttpException If the 'id' parameter is missing.
     * @throws NotFoundHttpException   If no Employee is found.
     */
    private function getEmployeeFromRequest(Request $request, EntityManagerInterface $em): Employee
    {
        $id = $request->query->get('id');

        if (!$id) {
            throw new BadRequestHttpException("Le paramètre 'id' est requis.");
        }

        $employee = $em->getRepository(Employee::class)->find($id);

        if (!$employee) {
            throw $this->createNotFoundException("Employé non trouvé.");
        }

        return $employee;
    }
}
