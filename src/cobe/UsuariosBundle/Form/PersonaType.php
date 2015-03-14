<?php

namespace cobe\UsuariosBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonaType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('doc_id')
            ->add('direccion')
            ->add('telefono')
            ->add('ciudad')
            ->add('intereses')
            ->add('aptitudes')
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
            'data_class' => 'cobe\UsuariosBundle\Entity\Persona'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'persona';
    }
}
