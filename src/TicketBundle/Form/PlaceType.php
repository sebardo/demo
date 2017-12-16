<?php

namespace TicketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CoreBundle\Form\ImageType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PlaceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $conf = array();
        if(isset($options['uploadDir'])){
            $conf['uploadDir'] = $options['uploadDir'];
        }
        $builder->add('name')
                ->add('slug', null, array('required' => false))
                ->add('description')
                ->add('technicalDetails')
                ->add('visible')
                ->add('active')
                ->add(
                    $builder->create('image', ImageType::class, $conf)
                )
                ->add('removeImage', HiddenType::class, array('required' => false, 'attr' => array(
                    'class' => 'remove-image'
                    )))
                ->add('dni')
                ->add('address')
                ->add('postalCode')
                ->add('city')
                ->add('metaTitle')
                ->add('metaDescription')
                ->add('metaTags')    
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TicketBundle\Entity\Place',
            'uploadDir' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ticketbundle_place';
    }


}
