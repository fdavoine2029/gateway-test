<?php

namespace App\Form\Sklbl;

use App\Entity\Sklbl\SklblLisageConfig;
use App\Entity\Sklbl\SklblStructure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SklblLisageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('sklblStructure', EntityType::class, [
                'class' => SklblStructure::class,
                'choice_label' => function (SklblStructure $structure): string {
                    return $structure->getName();
                }
                
            ])
            ->add('label', null, array(
                'label' => 'En-tête Csv', 
                'required' => true,
                'attr' => array('style' => 'width: 20rem')
            ))
            ->add('num', null, array(
                'label' => 'Position dans Csv', 
                'required' => true,
                'attr' => array('style' => 'width: 10rem')
            ))

            ->add('format', ChoiceType::class, [
                'choices'  => [
                    'Texte' => 'texte',
                    'Image' => 'image'
                ],
            ])
            ->add('value', null, array(
                'label' => 'Répertoire Image', 
                'required' => false,
                'attr' => array('style' => 'width: 40rem')
            ))

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => SklblLisageConfig::class,
            'cascade_validation' => true
            ));
    }
}
