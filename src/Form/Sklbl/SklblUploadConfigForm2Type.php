<?php

namespace App\Form\Sklbl;


use App\Entity\Sklbl\SklblUploadConfig;
use App\Repository\Sklbl\SklblUploadConfigRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SklblUploadConfigForm2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
        ->add('label', null, array(
            'label' => 'Champs sélectionné:', 

            'attr' => array('style' => 'width: 10rem','readonly' => true)
        ))
        ->add('id', EntityType::class, [
            'choice_label' => 'label',
            'class' => SklblUploadConfig::class,
            'mapped' => false,
            'label' => 'Sélectionner le champs à associer puis valider:', 
            'attr' => array('style' => 'width: 10rem'),
            'query_builder' => function(SklblUploadConfigRepository $cr){
                return $cr->createQueryBuilder('c')
                ->distinct('c.label')
                ->where('c.f1 = 1 and c.f2 = 1')
                ->andWhere('c.sklblOrder is null')
                ->orderby('c.label', 'ASC');
            }
        ]);
        

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SklblUploadConfig::class,
        ]);
    }
}
