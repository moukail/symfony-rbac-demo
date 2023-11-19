<?php

namespace App\Form;

use App\Entity\Permission;
use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                //'label' => 'Name',
                'attr' => [
                    'placeholder' => 'Name',
                ],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
            ])
            ->add('permissions', EntityType::class, [
                'class' => Permission::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'label',
                'label_attr' => [
                    'class' => 'checkbox-inline checkbox-switch'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
