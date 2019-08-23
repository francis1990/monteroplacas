<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni', 'text', array(
                'label' => 'DNI',
                'attr' => array('class' => 'form-control digits', 'placeholder' => 'DNI','maxLength'=>'11'),
                'required'=>false
            ))
            ->add('ruc','text', array(
                'attr' => array('class' => 'form-control digits', 'placeholder' => 'Ruc','maxLength'=>'11'),
                'required'=>false
            ))
            ->add('nombre', 'text', array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Nombre',),
                'required'=>true))
            ->add('razonsoc', 'text', array(
                'label' => 'Razón social',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Razón social',),
                'required'=>false))
            ->add('direccion', 'text', array(
                'label' => 'Dirección',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Dirección',),
                'required'=>false))
            ->add('telefono', 'text', array(
                'label' => 'Teléfono',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Teléfono',),
                'required'=>false))
            ->add('email', 'email', array(
                'label' => 'Email',
                'attr' => array('class' => 'form-control email', 'placeholder' => 'Email',),
                'required'=>false))
            ->add('tipo','text',array(
                'label' => 'Tipo',
                'attr' => array('class' => 'form-control', 'placeholder'=>'Tipo',),
                'required'=>false))
//            ->add('montodeuda', 'text', array(
//                    'label' => 'Monto deuda:',
//                    'attr' => array('class' => 'form-control', 'placeholder'=>'Monto deuda',),
//                'required'=>false))
//            ->add('fechacancelacion', 'date', [
//                'widget' => 'single_text',
//                'label' => 'Fecha de cancelación:',
//                'format' => 'dd/MM/yyyy',
//                'required'=>false,
//                'attr' => ['class' => 'form-control  dp_modal '],
//                'html5' => false,
//            ])
            ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Cliente'
        ));
    }
}
