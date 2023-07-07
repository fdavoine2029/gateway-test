<?php

namespace App\Form\Sklbl;

use App\Entity\Sklbl\SklblOrders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SklblMajorationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('qteLimit', null, array(
                'label' => 'Quantité minimum à produire par lot', 
                'required' => true,
                'attr' => array('style' => 'width: 20rem')
            ))
            ->add('percentAboveLimit', null, array(
                'label' => 'Pourcentage de majoration des quantités', 
                'required' => true,
                'attr' => array('style' => 'width: 20rem')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SklblOrders::class,
        ]);
    }
}
