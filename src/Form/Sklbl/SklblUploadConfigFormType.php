<?php

namespace App\Form\Sklbl;

use App\Entity\Sklbl\SklblStructure;
use App\Entity\Sklbl\SklblUploadConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SklblUploadConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sklblStructure', EntityType::class, [
                'label' => 'Identifiant', 
                'class' => SklblStructure::class,
                'choice_label' => function (SklblStructure $structure): string {
                    return $structure->getName();
                }
                
            ])
            ->add('label', null, array(
                'label' => 'Label', 
                'required' => true,
                'attr' => array('style' => 'width: 10rem')
            ))
            ->add('format', ChoiceType::class, [
                'required' => true,
                'attr' => array('style' => 'width: 10rem'),
                'choices'  => [
                    'Texte' => 'texte',
                    'Numerique' => 'numerique'
                ],
            ])
            ->add('uniqueValue', ChoiceType::class, [
                'label' => 'Unique',
                'required' => true,
                'attr' => array('style' => 'width: 10rem'),
                'choices'  => [
                    'Non' => 0,
                    'Oui' => 1
                ],
            ])
            ->add('customer', CheckboxType::class, [
                'label'    => 'Csv Client',
                'required' => false,
            ])
            ->add('customerCsv', null, array(
                'label' => 'Csv', 
                'required' => false,
                'attr' => array('style' => 'width: 5rem')
            ))
            ->add('f1', CheckboxType::class, [
                'label'    => 'Csv F1',
                'required' => false,
            ])
            ->add('f1Csv', null, array(
                'label' => 'F1 Csv', 
                'required' => false,
                'attr' => array('style' => 'width: 5rem')
            ))
            ->add('f2', CheckboxType::class, [
                'label'    => 'Csv F2',
                'required' => false,
            ])
            ->add('f2Csv', null, array(
                'label' => 'F2 Csv', 
                'required' => false,
                'attr' => array('style' => 'width: 5rem')
            ))
            ->add('f3', CheckboxType::class, [
                'label'    => 'Csv F3',
                'required' => false,
            ])
            ->add('f3Csv', null, array(
                'label' => 'F3 Csv', 
                'required' => false,
                'attr' => array('style' => 'width: 5rem')
            ))
            ->add('f4', CheckboxType::class, [
                'label'    => 'Csv F4',
                'required' => false,
            ])
            ->add('f4Csv', null, array(
                'label' => 'F4 Csv', 
                'required' => false,
                'attr' => array('style' => 'width: 5rem')
            ))
            ->add('f5', CheckboxType::class, [
                'label'    => 'Csv F5',
                'required' => false,
            ])
            ->add('f5Csv', null, array(
                'label' => 'F5 Csv', 
                'required' => false,
                'attr' => array('style' => 'width: 5rem')
            ))
            ->add('lisage', CheckboxType::class, [
                'label'    => 'Csv Lisage',
                'required' => false,
            ])
            ->add('lisageCsv', null, array(
                'label' => 'Lisage Csv', 
                'required' => false,
                'attr' => array('style' => 'width: 5rem')
            ))
            ->add('delete', SubmitType::class, [
                'attr' => ['class' => 'delete btn btn-danger'],
            ])
            ->add('id', null, [
                'required' => false,
            ])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => SklblUploadConfig::class,
            'cascade_validation' => true
            ));
    }
}
