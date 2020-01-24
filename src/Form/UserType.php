<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class,[
                'required'=>true,
                'label'=>'Identifiant',
                'attr'=> ['placeholder' => 'Identifiant...','maxlength'=>50]
            ])
            ->add('email',EmailType::class,[
                'required'=>true,
                'label'=>'Email',
                'attr'=>['placeholder'=>'Email...','maxlength'=>150]
            ])
            ->add('password',PasswordType::class,[
                'required'=>true,
                'label'=>'Mot de passe',
                'attr'=>['placeholder'=>'Mot de passe...','maxlength'=>50]
            ])
            ->add('submit', SubmitType::class,[
                "label"=>"S'inscrire"
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
