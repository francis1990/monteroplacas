<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GastoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fecha', 'date',array(
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'required'=>true,
                'attr' => array('class' => 'form-control  dp_modal '),
                'html5' => false,
            ))
            ->add('motivo','text', array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Motivo',)
            ))

            ->add('nombre','text', array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Nombre',)
            ))

            ->add('cantidad','number', array(
                'label' => 'Cantidad:',
                'attr' => array('class' => 'form-control number mayorcero', 'placeholder' => 'Cantidad',)
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Gasto'
        ));
    }
}
