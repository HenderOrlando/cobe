<?php

namespace cobe\EstadisticasBundle\Form;

use cobe\CommonBundle\Form\ObjectType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstadisticaType extends ObjectType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addObjectForm($builder, $options);

        $builder
            ->add('caracteristicas')
            ->add('estado')
            ->add('estadisticasAptitud')
            ->add('estadisticasEmpresa')
            ->add('estadisticasEstudiante')
            ->add('estadisticasGrupo')
            ->add('estadisticasInteres')
            ->add('estadisticasMensaje')
            ->add('estadisticasOfertaLaboral')
            ->add('estadisticasPublicacion')
            ->add('estadisticasUsuario')
            ->add('etiquetas')
            ->add('tipo')
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
            'data_class' => 'cobe\EstadisticasBundle\Entity\Estadistica'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'estadistica';
    }
}
