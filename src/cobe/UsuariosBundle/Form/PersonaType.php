<?php

namespace cobe\UsuariosBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonaType extends UsuarioType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $this->getBuilderPersonaForm($builder, $options);

        $builder->setMethod($this->method);
        $this->addSubmit($builder);
    }

    public function getBuilderPersonaForm($builder, $options){
        $this->getBuilderUsuarioForm($builder,$options);

        $builder
            ->add('apellidos')
            ->add('aptitudes')
            ->add('ciudad')
            ->add('direccion')
            ->add('doc_id')
            ->add('empresas')
            ->add('estudios')
            ->add('fechaNace')
            ->add('gruposPersona')
            ->add('idiomas')
            ->add('intereses')
            ->add('nombres')
            ->add('proyectos')
            ->add('recomendados')
            ->add('reconocimientos')
            ->add('telefono')
        ;
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
