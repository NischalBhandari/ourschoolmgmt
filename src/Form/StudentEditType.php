<?php

namespace App\Form;

use App\Entity\Student;
use App\Entity\Staff;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StudentEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
                $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('class', IntegerType::class)
            ->add('section', TextType::class)
            ->add('admission_date',DateType::class,[
                'widget' => 'choice',
            ])
            ->add('classteacher', EntityType::class,[
                'class'=>Staff::class,
            ])
            ->add('parent',TextType::class)
            ->add('phone',NumberType::class)
;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
