<?php

namespace Sistemadmin\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;

class ArticuloVentaType extends AbstractType
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
                'attr' => array('class' => 'form-control cantidad-embed number mayorcero', 'placeholder' => 'Cantidad')
            ))
            ->add('precio','number', array(
                'label' => 'Precio',
                'attr' => array('class' => 'form-control precio-embed number', 'placeholder' => 'Precio','readonly'=>false)
            ))
            ->add('articulo','entity', array(
                'label' => 'Artículo',
                'class' => 'BackendBundle:Articulo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')->where('u.utilizado != false OR u.utilizado IS NULL');
                    },
                'choice_attr' => function ($articulo) {
                    return array(
                        'data-pventa' => $articulo->getPreciodeventa(),
                        'data-pmayor' => $articulo->getPrecioventalpormayor(),
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
                'attr' => array('class' => 'form-control importe-embed number', 'placeholder' => 'Importe','readonly'=>true)
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sistemadmin\BackendBundle\Entity\ArticuloVenta'
        ));
    }
}
