<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
              'label' => 'Prénom'
            ])
            ->add('lastName', TextType::class, [
              'label' => 'Nom'
            ])
            ->add('email', EmailType::class, [
              'label' => 'Email'
            ])
            ->add('linkedinUrl', null, [
              'label' => 'Compte LinkedIn'
            ])
        ;
        if($options['show_roles']){
          $builder->add('roles', ChoiceType::class, [
            'label' => 'Rôle(s)',
            'multiple' => true,
            'choices' => [
              'Administrateur' => 'ROLE_ADMIN',
              'Etudiant' => 'ROLE_STUDENT'
            ],
          ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'show_roles' => false
        ]);
    }
}
