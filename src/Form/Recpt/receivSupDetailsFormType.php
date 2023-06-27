<?php

namespace App\Form\Recpt;

use App\Entity\OrderStatus;
use App\Entity\OrderSup;
use App\Entity\ReceivSup;
use App\Entity\ReceivSupDetails;
use App\Repository\OrderStatusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class receivSupDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orderSup', EntityType::class, [
                'class' => OrderSup::class,
                'choice_label' => 'id'
                
            ])
            ->add('numBlFou')
            ->add('qteRecue')
            ->add('comment')
            ->add('batch_num',null,array('attr' => array('readonly' => true)))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Conforme' => 1,
                    'Non conforme' => 2
                ],
            ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReceivSupDetails::class,
        ]);
    }
}
