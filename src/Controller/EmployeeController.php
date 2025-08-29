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

final class EmployeeController extends AbstractController
{
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

    #[Route('/team', name: 'team')]
    public function index(EmployeeRepository $employeeRepo): Response
    {
        $employees = $employeeRepo->findBy(['active' => true]);

        return $this->render('employee/index.html.twig', [
            'employees' => $employees,
        ]);
    }

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
}
