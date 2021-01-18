<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use	Symfony\Component\Form\Extension\Core\Type\TextType;
use	Symfony\Component\Form\Extension\Core\Type\TextareaType;
use	Symfony\Component\Form\Extension\Core\Type\NumberType;
use	Symfony\Component\Form\Extension\Core\Type\CollectionType;
use	Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
              'label' => 'Nom du projet',
            ])
            ->add('description', TextareaType::class, [
              'label' => 'Description du projet',
            ])
            ->add('short_description', TextType::class, [
              'label' => 'Courte description du projet (120 caractères, comme sur Twitter)',
            ])
            ->add('githubUrl', TextType::class, [
              'label' => 'URL Github (s\'il y a lieu)',
            ])
            ->add('cost', NumberType::class, [
              'label' => 'Coût estimé du projet',
            ])
            ->add('users', null, [
              'label' => 'Membres du projet',
            ])
            ->add('promotion', null, [
              'label' => 'Votre promotion',
            ])
            ->add('thumbnail_file', FileType::class, [
              'label' => 'Image de présentation',
              'mapped' => false,
              'required' => false,
              'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Merci d\'envoyer un véritable fichier JPEG, PNG ou GIF',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
