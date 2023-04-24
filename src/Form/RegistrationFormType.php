<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label'=>false,
                'attr'=>[
                    'autocomplete'=>'email',
                    'class'=>'bg-green-50 block my-10 mx-auto w-full lg:w-3/5 p-2 text-xl rounded-md outline-none border-transparent border-2 hover:border-green-400 delay-100 transition',
                    'placeholder'=> 'your@email.com'
                ]
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=>'accept terms ',
                'attr'=>[
                    'class'=>'mt-5'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Please accept terms of service.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                'class'=>'bg-green-50 block my-10 mx-auto w-full lg:w-3/5 p-2 text-xl rounded-md outline-none border-2 border-transparent hover:border-green-400 delay-100 transition',
                'placeholder'=> 'password'
            ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Password must be at least {{ limit }} chars long',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
