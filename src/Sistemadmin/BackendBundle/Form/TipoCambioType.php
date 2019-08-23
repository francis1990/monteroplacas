<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TipoCambioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('compra','money', array(
                'currency'=>false,
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Compra')
            ))
            ->add('venta','money', array(
                'currency'=>false,
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Venta')
            ))
            ->add('fecha', 'date',[
                'widget' => 'single_text',
                'label' => 'Fecha',
                'format' => 'MM/dd/yyyy',
                'required'=>true,
                'attr' => ['class' => 'form-control  dp_modal '],
                'html5' => false,
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\TipoCambio'
        ));
    }
}
