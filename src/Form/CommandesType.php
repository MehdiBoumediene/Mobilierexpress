<?php

namespace App\Form;

use App\Entity\Commandes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label' => false
                ])
            ->add('telephone',TextType::class, [
                'label' => false
                ])
           
            ->add('etage',ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    "4" => "4",
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    'plus' => 'plus',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => false
            ])
            ->add('couleur',ChoiceType::class, [
                'choices' => [
                    'Marron' => 'Marron',
                    'Gris' => 'Gris',
                    'Beige' => 'Beige',
                    "Bleu" => "Bleu",
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => false
            ])

            ->add('dimentions',ChoiceType::class, [
                'choices' => [
                    '160' => '160',
                    '180' => '180',
                    '200' => '200',
                    
                ],
                'expanded' => false,
                'multiple' => false,
                'required' => false,
                'label' => false
            ])
        
            ->add('Email',EmailType::class, [
               
                ])
            ->add('produit', EntityType::class, [
                'class' => Produits::class,
                'label' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
