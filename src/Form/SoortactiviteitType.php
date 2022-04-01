<?php
// src/AppBundle/Form/SoortactiviteitType
namespace App\Form;
use App\Entity\Soortactiviteit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;


use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoortactiviteitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('naam')
            ->add('minleeftijd')
            ->add('tijd', TimeType::class, ['attr' => ['class' => 'js-timepicker', 'placeholder'=>'hh:mm'],
                'widget'=>'single_text','html5' => false,])
            ->add('prijs');
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Soortactiviteit::class,
        ));

    }

}
