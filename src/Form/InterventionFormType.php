<?php

namespace App\Form;

use App\Entity\Interventions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('au_service')
            ->add('service_demandeur')
            ->add('code_imp')
            ->add('reference')
            ->add('date')
            ->add('batiment')
            ->add('local')
            ->add('description')
            ->add('piece_jointe')
            ->add('deg_urgence')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interventions::class,
        ]);
    }
}
