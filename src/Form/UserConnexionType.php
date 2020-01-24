<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserConnexionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',TextType::class,[
                'required'=>true,
                'label'=>'Adresse Email',
                'attr'=> ['placeholder' => 'Adresse Email...','maxlength'=>50]
            ])
            ->add('password',PasswordType::class,[
                'required'=>true,
                'label'=>'Mot de passe',
                'attr'=>['placeholder'=>'Mot de passe...','maxlength'=>50]
            ])
            ->add('submit', SubmitType::class,[
                "label"=>"Se connecter"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'trim' => true,
        ]);
    }
}
