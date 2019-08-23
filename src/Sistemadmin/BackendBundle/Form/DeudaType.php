<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeudaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numerofactura')
            ->add('totalapagar')
            ->add('fechainicio', 'date')
            ->add('fechacancelacion', 'date')
            ->add('plazoparapagar', 'date')
            ->add('tienedeuda')
            ->add('deuda')
            ->add('cliente')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Deuda'
        ));
    }
}
