<?php

namespace App\Form\Sklbl;

use App\Entity\Sklbl\SklblUploadConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SklblUploadConfigFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('columnName', null, array(
                'label' => 'Nom', 
                'required' => true,
                'attr' => array('style' => 'width: 20rem')
            ))
            ->add('columnLabel', null, array(
                'label' => 'Label', 
                'required' => true,
                'attr' => array('style' => 'width: 20rem')
            ))
            ->add('columnCsv', null, array(
                'label' => 'Colonne du csv (Lettre)', 
                'required' => true,
                'attr' => array('style' => 'width: 20rem','maxlength'=>3)
            ))
            ->add('lisage', CheckboxType::class, [
                'label'    => 'Variable lisage?',
                'required' => false,
                'attr' => array('style' => 'margin-top: 3rem')
            ]);
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
