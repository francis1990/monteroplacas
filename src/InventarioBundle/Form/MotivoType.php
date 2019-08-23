<?php

namespace InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MotivoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
                'label' => 'Nombre:',
                'required' => true,
                'attr' => array('placeholder' => 'Nombre', 'class' => 'form-control ','maxLength'=>'100')
            ))
            ->add('abreviatura', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
                'label' => 'Abreviatura:',
                'required' => true,
                'attr' => array('placeholder' => 'Abreviatura', 'class' => 'form-control ','maxLength'=>'50')
            ))
            ->add('descripcion', 'Symfony\Component\Form\Extension\Core\Type\TextareaType', array(
                'label' => 'Descripción:',
                'required' => false,
                'attr' => array('placeholder' => 'Descripción', 'class' => 'form-control','maxLength'=>'255')
            ))
            ->add('tipo', 'Symfony\Component\Form\Extension\Core\Type\ChoiceType', array(
                'choices' => array(
                    1 => 'Entrada',
                    (-1) => 'Salida'
                ),
                'label' => 'Tipo:',
                'placeholder' => 'Seleccione...',
                'attr' => array('class' => 'form-control chosen-select')
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InventarioBundle\Entity\Motivo'
        ));
    }
}
