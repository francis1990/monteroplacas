<?php

namespace Sistemadmin\BackendBundle\Form;

use Sistemadmin\BackendBundle\Entity\ArticuloVenta;
use Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito;
use Sistemadmin\BackendBundle\Form\VentaType;


use Sistemadmin\BackendBundle\Entity\NotaDebito;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;

class ArticuloNotaDebitoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seccion', 'entity', array(
                'class' => 'InventarioBundle:Seccion',
                'attr' => array('class' => 'form-control', 'readonly' => true)
            ))
            ->add('articulo','entity', array(
                'class' => 'BackendBundle:Articulo',
                'attr' => array('class' => 'form-control text', 'readonly' => true)))
            ->add('precio','number', array(
                'attr' => array('class' => 'form-control number', 'readonly' => false)
            ))
            ->add('cantidad','number', array(
                'attr' => array('class' => 'form-control number', 'readonly' => false)
            ))
            ->add('importe','number', array(
                'attr' => array('class' => 'form-control number', 'readonly' => true)
            ))
        ;
        
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito'
        ));
    }
}
