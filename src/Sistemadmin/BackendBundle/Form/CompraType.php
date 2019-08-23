<?php

namespace Sistemadmin\BackendBundle\Form;

use Proxies\__CG__\Sistemadmin\BackendBundle\Entity\ArticuloVenta;
use Sistemadmin\BackendBundle\Entity\ArticuloCompra;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;

class CompraType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidaddearticulo', 'number', array(
                'label' => 'Cantidad de Artículos',
                'attr' => array('class' => 'form-control number mayorcero', 'placeholder' => 'Cantidad de Artículos', 'readonly' => false)
            ))
            ->add('fechacompra', 'date', [
                'widget' => 'single_text',
                'label' => 'Fecha:',
                'format' => 'MM/dd/yyyy',
                'required' => true,
                'attr' => ['class' => 'form-control  dp_modal '],
                'html5' => false,
            ])
            ->add('montototalpagado', 'number', array(
                'label' => 'Monto pagado',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Monto pagado', 'readonly' => false)
            ))
            ->add('moneda', 'choice', array(
                'choices' => array(
                    'Soles' => 'soles',
                    'Dolares' => 'dolares',
                ),
                'attr' => array('class' => 'form-control chzn_b'),
                'choices_as_values' => true,
            ))

            ->add('proveedor', 'entity', array(
                'label' => 'Proveedor',
                'class' => 'BackendBundle:Proveedor',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')->where('u.utilizado != false OR u.utilizado IS NULL');
                    },
                'attr' => array('class' => 'form-control chzn_b'),
                'choice_attr' => function ($proveedor) {
                    return array(
                        'data-ruc' => $proveedor->getRuc(),
                    );
                },
            ))
            ->add('articulocompras', 'Symfony\Component\Form\Extension\Core\Type\CollectionType', array(
                'entry_type' => 'Sistemadmin\BackendBundle\Form\ArticuloCompraType',
                'prototype_data' => new ArticuloCompra(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true,
                'error_bubbling' => false,
                'label' => 'Artículos',

            ))
            ->add('centrodecosto', 'choice', array(
                'choices' => array(
                    'Administración'=>'Administración',
                    'Contabilidad'=>'Contabilidad' ,
                    'Logística'=>'Logística',
                ),
                'label' => 'Centro de costo',
                'attr' => array('class' => 'form-control chzn_b'),
                'choices_as_values' => true,
            ))
            ->add('formadepago', 'choice', array(
                'choices' => array(
                    'Contado' =>'Contado',
                    'Crédito'=>'Crédito'
                ),
                'label' => 'Forma de pago',
                'attr' => array('class' => 'form-control chzn_b'),
                'choices_as_values' => true,
            ))
            ->add('numerofactura', 'text', array(
                'label' => 'Número de factura',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Número de factura')
            ))
            ->add('tiempoentrega', 'integer', array(
                'label' => 'Tiempo de entrega',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Tiempo de entrega')
            ))
            ->add('garantia', 'choice', array(
                'choices' => array(
                    '1 mes',
                    '3 meses',
                    '6 meses',
                    '1 año'
                ),
                'label' => 'Garantía',
                'attr' => array('class' => 'form-control chzn_b')
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Compra'
        ));
    }
}
