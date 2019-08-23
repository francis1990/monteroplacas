<?php

namespace Sistemadmin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RolType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', 'text', array(
                'label' => 'Descripción',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Descripción',)
            ))
            ->add('activo', 'checkbox', array(
                'label' => 'Activo:',
                'required' => false,
                'attr' => array('class' => 'form-control')
            ));

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\AdminBundle\Entity\Rol'
        ));
    }
}
