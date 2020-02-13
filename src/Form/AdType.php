<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Titre...', 'maxlength' => 100]
            ])

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
            ])
        
            ->add('description',TextareaType::class, [
                'required' => true,
                'label' => 'Description',
                'attr' => ['placeholder' => 'Description de votre annonce...', 'rows' => '5']
            ])
            ->add('city',TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Ville...', 'maxlength' => 70]
            ])
            ->add('zip',IntegerType::class, [
                'required' => true,
                'label' => 'Code postal',
                'attr' => ['placeholder' => 'Code postal...', 'maxlength' => 5]
            ])
            ->add('price', MoneyType::class, [
                'required' => true,
                'label' => 'Prix',
                'attr' => ['placeholder' => 'Prix...']
            ])
            ->add('date_fin',DateTimeType::class, [
                'required' => true,
                'label' => 'Date de fin ( vous pourrez stoper la vente a tout moment )',
            ])
            ->add('imagePath',FileType::class, [
                'required' => false,
                'label' => 'Image de annonce',
                'attr'=>['accept'=> 'image/png, image/jpeg']
            ])
            ->add('submit', SubmitType::class,[
                "label"=>"Envoyer"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
            'trim' => true,
        ]);
    }
}
