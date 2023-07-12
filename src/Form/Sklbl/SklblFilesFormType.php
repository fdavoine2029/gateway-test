<?php

namespace App\Form\Sklbl;

use App\Entity\Sklbl\SklblFiles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SklblFilesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idColumn', null, array(
                'label' => 'Identifiant', 
                'attr' => array('style' => 'width: 15rem')
            ))
            ->add('vendorColumn', null, array(
                'label' => 'Façonnier', 
                'attr' => array('style' => 'width: 15rem')
               ))
            ->add('skuColumn', null, array(
                'label' => 'Lot (Sku)', 
                'attr' => array('style' => 'width: 15rem')
               ))
            ->add('skuTisseColumn', null, array(
                'label' => 'Variable à tisser', 
                'attr' => array('style' => 'width: 15rem')
               ))
            ->add('qteColumn', null, array(
                'label' => 'Quantité', 
                'attr' => array('style' => 'width: 15rem')
               ))
            ->add('clientFilename', FileType::class, [
                'label' => 'Fichier client (xlsx file)',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                'attr' => array('style' => 'width: 50rem'),
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid xls document',
                    ])
                ],
            ])
            ->add('deleteSku', CheckboxType::class, [
                'label'    => 'Supprimer les skus non transféré?',
                'required' => false,
            ])
            ->add('ligne', null, array(
                'label' => 'A partir de la ligne', 
                'attr' => array('style' => 'width: 20rem')
               ))
            //->add('sklblOrder')
            //->add('sklblOf')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SklblFiles::class,
        ]);
    }
}
