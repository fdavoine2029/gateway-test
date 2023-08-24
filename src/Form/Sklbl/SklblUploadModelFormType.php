<?php

namespace App\Form\Sklbl;

use App\Entity\Sklbl\SklblModel;
use App\Entity\Sklbl\SklblUploadConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SklblUploadModelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('SklblModel', EntityType::class, [
            'label' => 'Sélectionner un modèle', 
            'class' => SklblModel::class,
            'choice_label' => function (SklblModel $model): string {
                return $model->getName();
            }
            
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SklblUploadConfig::class,
        ]);
    }
}
