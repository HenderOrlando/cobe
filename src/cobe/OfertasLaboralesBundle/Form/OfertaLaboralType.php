<?php

namespace cobe\OfertasLaboralesBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfertaLaboralType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('estadoOfertasLaborales')
            ->add('fechaLimite')
            ->add('publicacion')
            ->add('tipoOfertasLaborales')
            ->add('usuario')
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
            'data_class' => 'cobe\OfertasLaboralesBundle\Entity\OfertaLaboral'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oofertalaboral';
    }
}
