<?php

namespace App\Form\Sklbl;

use App\Entity\Articles;
use App\Entity\Sklbl\SklblOf;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SklblOfFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          //  ->add('id')
          //  ->add('dossier')
            //->add('code')
            //->add('refCli')
            //->add('orderQte')
            //->add('launchedQte')
            //->add('sref1')
            //->add('sref2')
            //->add('sync')
            //->add('created_at')
            //->add('updated_at')
            //->add('ofStatus')
            //->add('sklblStatus')
            //->add('planned_at')
            //->add('start_at')
            //->add('end_at')
            //->add('orderNum')
            //->add('article')
            //->add('client')
            //->add('emballage1')
            //->add('emballage2')
            //->add('emballage3')
            //->add('emballage4')
            //->add('fichier1')
            //->add('fichier2')
            //->add('miniComplet')
            //->add('masque')
            //->add('fichierRetour')
            //->add('options')
            //->add('sklblOrder')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SklblOf::class,
        ]);
    }
}
