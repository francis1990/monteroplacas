<?php

namespace InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransferenciaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidad', 'Symfony\Component\Form\Extension\Core\Type\NumberType', array(
                'attr' => array('class' => 'form-control','placeholder'=>'Cantidad'),
            ))
            ->add('articulo','entity', array(
                'label'=>'Artículo',
                'placeholder'=>'Seleccione...',
                'class' => 'BackendBundle:Articulo',
                'attr' => array('class' => 'form-control chosen-select')))
            ->add('fecha', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
                'widget' => 'single_text',
                'label' => 'Fecha',
                'format' => 'dd/MM/yyyy',
                'required'=>true,
                'attr' => array('class' => 'form-control datepicker'),
                'html5' => false,
            ))
            ->add('seccionini','entity', array(
                'label'=>'Sección actual',
                'class' => 'InventarioBundle:Seccion',
                'placeholder'=>'Seleccione...',
                'attr' => array('class' => 'form-control chosen-select')))
            ->add('seccionfin','entity', array(
                'label'=>'Sección destino',
                'class' => 'InventarioBundle:Seccion',
                'placeholder'=>'Seleccione...',
                'attr' => array('class' => 'form-control chosen-select')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InventarioBundle\Entity\Transferencia'
        ));
    }
}
