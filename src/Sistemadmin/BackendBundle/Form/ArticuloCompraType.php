<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticuloCompraType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidad','number', array(
                'label' => 'Cantidad',
                'attr' => array('class' => 'form-control number cantidad-embed mayorcero', 'placeholder' => 'Cantidad')
            ))
            ->add('precio','number', array(
                'label' => 'Precio',
                'attr' => array('class' => 'form-control number precio-embed', 'placeholder' => 'Precio','readonly'=>false)
            ))
            ->add('articulo','entity', array(
                'label' => 'Articulo',
                'class' => 'BackendBundle:Articulo',
                'choice_attr' => function ($articulo) {
                    return array(
                        'data-pcompra' => $articulo->getPreciodecompra(),
                        'data-pmayor' => $articulo->getPreciocompralpormayor(),
                    );
                },
                'attr' => array('class' => 'form-control chzn_b articulo-embed')))

            ->add('seccion', 'entity', array(
                'label' => 'Sección',
                'class' => 'InventarioBundle:Seccion',
                'attr' => array('class' => 'form-control chzn_b')
            ))
            ->add('importe','number', array(
                'label' => 'Importe',
                'attr' => array('class' => 'form-control number importe-embed', 'placeholder' => 'Importe','readonly'=>true)
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\ArticuloCompra'
        ));
    }
}
