<?php

namespace App\Form;

use App\Entity\Interventions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\File as AssertFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


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
            ->add('piece_jointe',FileType::class, [
                'label' => 'Importer une piece jointe',
                'required' => false,
                'mapped' => false,
                ])
                ->add('deg_urgence', ChoiceType::class, [
                    'choices' => [
                        'Immediat' => 'immediat',
                        '3 jours' => '3jours',
                        '15 jours' => '15jours',
                        'Mois' => 'mois',
                        'Immediat' => 'immediat',
                    ],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Interventions::class,
        ]);
    }
}
