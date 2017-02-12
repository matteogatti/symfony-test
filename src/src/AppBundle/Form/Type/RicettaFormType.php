<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Ricetta;
use AppBundle\Form\DataTransformer\IngredienteToIdTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RicettaFormType extends AbstractType
{
    private $request;
    private $entityManager;

    public function __construct(RequestStack $request, EntityManager $entityManager)
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nome')
            ->add('ingredienti', TextType::class, array(
                'label' => false,
                'attr' => array('class' => 'hidden'),
                'required' => false
            ))
            ->add('magicsuggest', TextType::class, array(
                'mapped' => false,
                'label' => 'Ingredienti'
            ))
            ->get('ingredienti')->addModelTransformer(new IngredienteToIdTransformer($this->request, $this->entityManager));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ricetta::class
        ));
    }
}
