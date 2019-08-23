<?php

namespace InventarioBundle\Form;

use InventarioBundle\Repository\MotivoRepository;
use InventarioBundle\Repository\MovimientoRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovimientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidad', 'Symfony\Component\Form\Extension\Core\Type\NumberType', array(
                'attr' => array('class' => 'form-control','placeholder'=>'Cantidad'),
            ))
           ->add('seccion','entity', array(
                'label'=>'Sección',
                'class' => 'InventarioBundle:Seccion',
                'placeholder'=>'Seleccione...',
                'attr' => array('class' => 'form-control chosen-select')))
          ->add('motivo','entity', array(
                'class' => 'InventarioBundle:Motivo',
                'placeholder'=>'Seleccione...',
                'attr' => array('class' => 'form-control chosen-select'),
              'query_builder' => function (MotivoRepository $ngr) {
                  return $ngr->createQueryBuilder('m')
                      ->where('m.conceptodefault = 0')
                      ->orderBy('m.nombre', 'ASC');
              }))
           ->add('articulo','entity', array(
                'label'=>'Artículo',
                'placeholder'=>'Seleccione...',
               'class' => 'BackendBundle:Articulo',
                'attr' => array('class' => 'form-control chosen-select')))
            ->add('fecha', 'Symfony\Component\Form\Extension\Core\Type\DateType', array(
                'widget' => 'single_text',
                'label' => 'Fecha',
                'format' => 'dd/MM/yyyy',
                'required'=>true,
                'attr' => array('class' => 'form-control datepicker'),
                'html5' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'InventarioBundle\Entity\Movimiento'
        ));
    }
}
