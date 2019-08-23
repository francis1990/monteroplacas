<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', 'text', array(
                'label' => 'Código',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Código',)
            ))
            ->add('tipodocumento', 'text', array(
                'label' => 'Tipo de documento',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Tipo de documento',)
            ))
            ->add('serie1','number', array(
                'label' => 'Serie 1',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Serie 1',)
            ))
        ->add('serie2','number', array(
        'label' => 'Serie 2',
        'attr' => array('class' => 'form-control number', 'placeholder' => 'Serie 2',)
    ))
        ->add('serie3','number', array(
            'label' => 'Serie 3',
            'attr' => array('class' => 'form-control number', 'placeholder' => 'Serie 3',)
        ))
        ->add('numero1','number', array(
            'label' => 'Número 1',
            'attr' => array('class' => 'form-control number', 'placeholder' => 'Número 1',)
        ))
        ->add('numero2','number', array(
            'label' => 'Número 2',
            'attr' => array('class' => 'form-control number', 'placeholder' => 'Número 2',)
        ))
        ->add('numero3','number', array(
            'label' => 'Número 3',
            'attr' => array('class' => 'form-control number', 'placeholder' => 'Número 3',)
        ))
        ->add('numerodelineas','number', array(
            'label' => 'Número de lineas',
            'attr' => array('class' => 'form-control number', 'placeholder' => 'Número de lineas',)
        ))
        ->add('igv','checkbox', array(
            'label' => 'IGV:',
            'required'=>false,
            'attr' => array('class' => 'form-control', 'placeholder' => 'IGV',)
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Documento'
        ));
    }
}
