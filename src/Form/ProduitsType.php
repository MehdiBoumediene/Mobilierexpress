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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use PhpParser\Node\Stmt\Label;
class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, [
                'label' => false,
            ])
            ->add('dispo',CheckboxType::class, [
                'label' => 'Disponible ',
                'required'=>false,
            ])
            ->add('accueil',CheckboxType::class, [
                'label' => "Afficher à l'accueil ",
                'required'=>false,
            ])
            ->remove('slug')
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
                'label' => false,
                'choice_label' => 'nom',
                'empty_data'=>'',
                'required'=>false,
         
            ])
            ->add('description',CkEditorType::class, [

                'label' => false,
                ])
            ->add('prix',TextType::class, [
                'label' => false,
            ])
            ->add('files',FileType::class,[
                'label' => false,
                'multiple' => true,
                'mapped'=> false,
                'required'=> false,
                
        
            
            ])

            ->add('videos',FileType::class,[
                'label' => false,
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
