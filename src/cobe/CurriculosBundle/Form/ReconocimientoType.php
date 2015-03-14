<?php

namespace cobe\CurriculosBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReconocimientoType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('fechaOtorgado')
            ->add('tipo')
            ->add('empresas')
        ;

        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\CurriculosBundle\Entity\Reconocimiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cobe_curriculosbundle_reconocimiento';
    }
}
