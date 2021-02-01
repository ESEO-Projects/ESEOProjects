<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class CreateUserType extends AbstractType
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
          ->add('plainPassword', PasswordType::class, [
            'label' => 'Mot de passe',
            'mapped' => false
          ])
          ->add('email', EmailType::class, [
            'label' => 'Email'
          ])
          ->add('linkedinUrl', null, [
            'label' => 'Compte LinkedIn'
          ])

      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
