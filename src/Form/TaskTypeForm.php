<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Status;
use App\Entity\Tag;
use App\Entity\Task;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class TaskTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la tâche',
                'required' => true,
                'attr' => [
                    'id' => 'tache_titre',
                    'class' => '',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'id' => 'tache_description',
                ],
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Date',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'id' => 'tache_deadline',
                ],
            ]);

        // Dynamically add project-dependent fields
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $task = $event->getData();
            $form = $event->getForm();

            if (!$task || !$task->getProject()) {
                return;
            }

            $project = $task->getProject();

            $form->add('status', EntityType::class, [
                'class' => Status::class,
                'label' => 'Statut',
                'choices' => $project->getStatuses()->toArray(),
                'choice_label' => 'label',
                'attr' => ['id' => 'tache_statut'],
            ]);

            $form->add('employee', EntityType::class, [
                'class' => Employee::class,
                'label' => 'Membre',
                'required' => false,
                'placeholder' => '',
                'choices' => $project->getEmployees()->toArray(),
                'choice_label' => fn($employee) => $employee->getFirstName().' '.$employee->getLastName(),
                'attr' => ['id' => 'tache_employe'],
            ]);

            $submitLabel = $task && $task->getId() ? 'Modifier' : 'Ajouter';

            $form->add('submit', SubmitType::class, [
                'label' => $submitLabel,
                'attr' => ['class' => 'button button-submit'],
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
