<?php

namespace cobe\MensajesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MensajeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuarioMensaje')
            ->add('estadoMensaje')
            ->add('plantilla')
            ->add('mensajes')
            ->add('mensajesRespuesta')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'cobe\MensajesBundle\Entity\Mensaje'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cobe_mensajesbundle_mensaje';
    }
}
