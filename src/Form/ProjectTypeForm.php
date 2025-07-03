<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Employee;

class ProjectTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre du projet',
                'required' => true,
                'attr' => ['id' => 'projet_nom'],
            ])
            ->add('employes', EntityType::class, [
                'label' => 'Inviter des membres',
                'class' => Employee::class,
                'choice_label' => 'fullName',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'id' => 'projet_employes',
                    'multiple' => 'multiple',
                    'class' => 'select2',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
