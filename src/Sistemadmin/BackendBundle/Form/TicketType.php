<?php

namespace Sistemadmin\BackendBundle\Form;

use Sistemadmin\BackendBundle\Entity\ArticuloVenta;
use Sistemadmin\BackendBundle\Form\ArticuloVentaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('documento','entity', array(
                'class' => 'BackendBundle:Documento',
                'choice_attr' => function ($documento) {
                    return array(
                        'data-serie1' => $documento->getSerie1().'-'.$documento->getNumero1(),
                        'data-serie2' => $documento->getSerie2().'-'.$documento->getNumero2(),
                        'data-serie3' => $documento->getSerie3().'-'.$documento->getNumero3(),
                    );
                },
                'attr' => array('class' => 'form-control chzn_b')))
//            ->add('seriedoc', 'choice', array(
//                'label'=>'Serie',
//                'mapped'=>false,
//                'required'=>false,
//                'attr' =>array('class' => 'form-control ')
//            ))
            ->add('serie', 'number', array(

                'attr' => array('class' => 'form-control number', 'placeholder' => 'Serie')
            )) 
            ->add('numerodedocumento', 'number', array(
                'label' => 'Número',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Número')
            ))
            ->add('fecha', 'date',[
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'required'=>true,
                'attr' => ['class' => 'form-control  dp_modal '],
                'html5' => false,
            ])
            ->add('cliente','entity', array(
                'class' => 'BackendBundle:Cliente',
                'choice_attr' => function ($articulo) {
                    return array(
                        'data-cuenta' => $articulo->getNombre(),
                    );
                },
                'attr' => array('class' => 'form-control chzn_b')))
            ->add('vendedor','entity', array(
                'class' => 'BackendBundle:Vendedor',
                'attr' => array('class' => 'form-control chzn_b')))

            ->add('cantidaddearticulos', 'number', array(
                'label' => 'Cantidad de Artículos',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Cantidad de Artículos','readonly'=>false)
            ))
             ->add('centrodecosto', 'choice', array(
                'choices' => array(
                    'Administración' => 'administracion',
                    'Contabilidad' => 'contabilidad',
                    'Logística' => 'logistica',
                ),
                'label'=>'Centro de costo',
                'attr' =>array('class' => 'form-control chzn_b'),
                'choices_as_values' => true,
            ))
            ->add('formadepago', 'choice', array(
                'choices' => array(
                    'Contado' => 'contado',
                    'Credito' => 'credito'
                ),
                'label'=>'Forma de pago',
                'attr' =>array('class' => 'form-control chzn_b'),
                'choices_as_values' => true,
            ))
            ->add('observacion', 'textarea', array(
                'label' => 'Observación',
                'attr' => array('class' => 'form-control', 'placeholder' => 'Observación'),
                'required'=>false
            ))
            ->add('montototalapagar','number', array(
                'label' => 'Monto total a pagar',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Monto total a pagar','readonly'=>true)
            ))
//            ->add('totalrecibido','money', array(
//                'label' => 'Total recibido:',
//                'currency'=>false,
//                'attr' => array('class' => 'form-control', 'placeholder' => 'Total recibido',)
//            ))
            ->add('articuloventas', 'Symfony\Component\Form\Extension\Core\Type\CollectionType', array(
                'entry_type' => 'Sistemadmin\BackendBundle\Form\ArticuloVentaType',
                'prototype_data' => new ArticuloVenta(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => true,
                'error_bubbling' => false,
                'label' => 'Artículos:',

            ))




        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Venta'
        ));
    }
}
