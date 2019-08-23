<?php

namespace Sistemadmin\BackendBundle\Form;

use Sistemadmin\BackendBundle\Entity\Venta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;

class NotaCreditoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('venta',null,array(
        'class'=>'BackendBundle:Venta',
        'query_builder'=> function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')->where('u.anulada = false or u.anulada IS NULL');
                    },                  
        'label'=>'serie' . '-' . 'numerodedocumento',
                'attr' => array('class' => 'form-control Multiple')
    ));
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
