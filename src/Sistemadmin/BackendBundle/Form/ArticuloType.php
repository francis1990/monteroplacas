<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;


class ArticuloType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', 'text', array(
                'label' => 'Nombre',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Nombre',),
                ))
            ->add('abreviatura','text', array(
                'label' => 'Abreviatura',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Abreviatura',),
                'required' => false
                ))
            ->add('descripcion','textarea', array(
                    'required' => false,
                'label' => 'Descripción',
                'attr' => array('class' => 'form-control',),

            ))
            ->add('fechainsercion', 'date',array(
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'label' => 'Fecha de cancelación',
                'required'=>false,
                'attr' => array('class' => 'form-control  dp_modal '),
                'html5' => false,
            ))
            ->add('marca',null, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Marca',),
                'required' => false
                ))
            ->add('medida',null, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Medida',),
                'required' => false
                ))
            ->add('unidaddemedida',null, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Unidad de Medida',),
                'required' => false
                ))    
            ->add('modelo',null, array(
                'attr' => array('class' => 'form-control', 'placeholder' => 'Modelo',),
                'required' => false
                ))
            ->add('preciodeventa','money', array('currency'=>false,
                'label' => 'Precio de venta',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Precio de venta','min'=>0),
                ))
            ->add('preciodecompra','money', array('currency'=>false,
                'label' => 'Precio de compra',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Precio de compra','min'=>0),
                ))
            ->add('precioventalpormayor','money', array('currency'=>false,
                'label' => 'Precio de venta por mayor',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Precio de venta por mayor',),
                    'required' => false
                ))
            ->add('preciocompralpormayor','money', array('currency'=>false,
                'label' => 'Precio de compra por mayor',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Precio de compra por mayor',),
                    'required' => false
                ))
            ->add('file')
            ->add('proveedor',null, array(
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')->where('u.utilizado != false OR u.utilizado IS NULL');
                },
                /*'empty_value' => 'Select',*/
                'property' => 'nombre','required' => false
            ,'attr' => array('class' => 'form-control chzn_b','multiple'=>'')))
                
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Articulo'
        ));
    }
}
