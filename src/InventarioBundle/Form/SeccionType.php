<?php

namespace InventarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeccionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'Symfony\Component\Form\Extension\Core\Type\TextType', array(
                'label' => 'Denominación:',
                'required' => true,
                'attr' => array('placeholder' => 'Nombre', 'class' => 'form-control ','maxLength'=>'100')
            ))
            ->add('almacen','entity', array(
                'label' => 'Almacén:',
                'class' => 'InventarioBundle:Almacen',
                'attr' => array('class' => 'form-control chosen-select')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InventarioBundle\Entity\Seccion'
        ));
    }
}
