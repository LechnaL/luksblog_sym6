<?php

namespace App\Form;

use App\Entity\Post;
use Doctrine\DBAL\Types\SmallIntType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label'=> false,
                'attr'=>[
                'class'=>'bg-green-50 block my-10 mx-auto w-full lg:w-3/5 p-2 text-xl rounded-md outline-none border-transparent border-2 hover:border-green-400 delay-100 transition',
                'placeholder'=> 'title'
                ]
            ])
            ->add('content', TextareaType::class, [               
            'label'=> false,
            'attr'=>[
            'class'=>'bg-green-50 block my-10 mx-auto w-full lg:w-3/5 p-2 text-xl rounded-md outline-none border-transparent border-2 hover:border-green-400 delay-100 transition',
            'placeholder'=> 'content'
            ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
