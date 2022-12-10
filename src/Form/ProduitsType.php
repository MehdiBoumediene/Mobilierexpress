<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Categories;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('slug')
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
                'label' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])
            ->add('description')
            ->add('prix')
            ->add('files',FileType::class,[
                'label'=> 'Photos',
                'multiple' => true,
                'mapped'=> false,
                'required'=> false,
                
        
            
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
