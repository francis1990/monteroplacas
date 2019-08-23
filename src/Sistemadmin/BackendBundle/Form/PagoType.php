<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sistemadmin\BackendBundle\Repository\ClienteRepository;

class PagoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serie','number',array(
                'attr'=>array('class'=>'form-control', 'placeholder' => 'Serie')
            ))
                ->add('numerofactura','number',array(
                'attr'=>array('class'=>'form-control', 'placeholder' => 'Número')
            ))
//            ->add('totalapagar','money', array(
//                'label' => 'Total a pagar:',
//                'currency'=>false,
//                'data'=>0,
//                'attr' => array('class' => 'form-control', 'placeholder' => 'Total a pagar')
//            ))
            ->add('montopagado','money', array(
                'label' => 'Monto pagado',
                'currency'=>false,
                'attr' => array('class' => 'form-control number', 'placeholder' => 'Monto pagado')
            ))
            ->add('fechapago', 'date',[
                'widget' => 'single_text',
                'label' => 'Fecha',
                'format' => 'MM/dd/yyyy',
                'required'=>true,
                'attr' => ['class' => 'form-control  dp_modal '],
                'html5' => false,
            ])
            ->add('cliente','entity',array( 
                'class'=>'BackendBundle:Cliente',
                'attr' => array('class' => 'form-control chzn_b'),
                 'query_builder'=>  function(ClienteRepository $q){
                return $q->createQueryBuilder('u')   
                        ;
            }
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\Pago'
        ));
    }
}
