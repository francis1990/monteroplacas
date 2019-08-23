<?php

namespace Sistemadmin\BackendBundle\Form;

use Sistemadmin\BackendBundle\Entity\ArticuloVenta;
use Sistemadmin\BackendBundle\Form\ArticuloVentaType;
use Sistemadmin\BackendBundle\Form\VentaType;
use Sistemadmin\BackendBundle\Entity\ArticuloNotaDebito;

use Sistemadmin\BackendBundle\Entity\NotaDebito;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;

class NotaDebitoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serie', 'number', array(                                
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Serie')
            ))
            ->add('numerodedocumento', 'number', array(
                'label' => 'Número',
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Número')))
            ->add('fecha', 'date',[
                'widget' => 'single_text',
                'format' => 'MM/dd/yyyy',
                'required'=>true,
                'attr' => ['class' => 'form-control  dp_modal '],
                'html5' => false,
            ])
            ->add('tipo', 'choice', array(
                'choices' => array(
                    // 'Anulación de la operación' => 'Anulación de la operación',
                    // 'Anulación por error en el RUC' => 'Anulación por error en el RUC',
                    'Corrección por error en la descripción' => 'Corrección por error en la descripción',
                ),
                'label'=>'Tipo de nota de crédito: ',
                'attr' =>array('class' => 'form-control chzn_b'),
                'choices_as_values' => true,
            ))
            ->add('motivo', 'textarea', array(
                'label' => 'Motivo',
                'attr' => array(
                    'class' => 'form-control', 'placeholder' => 'Motivo'),
                    'required'=>false)
            )

            ->add('venta', 'entity', array(                
                'class' => 'BackendBundle:Venta',
                'query_builder' => function (EntityRepository $er) {
                        // return $er->createQueryBuilder('u')->where('u.anulada != false OR u.anulada IS NULL');
                        return $er->createQueryBuilder('u')->where('u.anulada != true OR u.anulada IS NULL');
                    },
                'label' => 'Número de Factura', 
                'choice_attr' => function ($venta) {
                    return array(
                        'data-venta-id' => $venta->getId(),'data-venta-cliente' => $venta->getCliente(), 
                        'data-venta-vendedor' => $venta->getVendedor(), 'data-venta-centrodecosto' => $venta->getCentrodecosto(), 
                        'data-venta-formadepago' => $venta->getFormadepago(), 'data-venta-observacion' => $venta->getObservacion(),
                        'data-venta-fecha' => $venta->getFecha()->format('d/m/Y')
                    );
                }, 
                            
                'attr' => array(
                    'class' => 'form-control chzn_b'))
            )
            
            ->add('articulonotadebitos', 'Symfony\Component\Form\Extension\Core\Type\CollectionType', array(
                'entry_type' => 'Sistemadmin\BackendBundle\Form\ArticuloNotaDebitoType',
                'prototype_data' => new ArticuloNotaDebito(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'error_bubbling' => false,
                'label' => ' ',
                'required' => false,

            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\NotaDebito'
        ));
    }
}