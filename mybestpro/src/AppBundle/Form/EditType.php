<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\Entity\Task;

class EditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
      ->add('Title', TextType::class, array(
          'label' => 'Titre',
          'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez remplir ce champs'
                ]),
            ]
      ))
      ->add('Description', TextareaType::class, array(
          'label' => 'Description',
          'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez remplir ce champs'
                ]),
            ]
      ))
      ->add('Status', ChoiceType::class, array(
          'label' => 'Statut',
          'placeholder' => '- Sélectionner le statut de votre tâche -',
          'choices'  => array(
              'Terminé' => 'Terminé',
              'En cours' => 'En cours',
              'A faire' => 'A faire',
          ),
      ))
      ->add('Changer la tâche', SubmitType::class, array('label' => 'Modifier'))
      ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Task'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_task';
    }


}
