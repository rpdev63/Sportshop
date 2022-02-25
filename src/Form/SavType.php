<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SavType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('civilite', ChoiceType::class, array(
                'choices' => array(
                    'M' => 'Monsieur',
                    'Mme' => 'Madame',

                ), 'expanded' => true,
                'multiple' => false
            ))
            ->add('email', EmailType::class)
            ->add('numeroCommande', TextType::class)
            ->add('motif', ChoiceType::class, array(
                'choices' => array(
                    'Article en mauvais état' => 'defectueux',
                    'Produit incomplet' => 'incomplet',
                    'Retard de livraison' => 'retard',
                )
            ))
            ->add('message', TextareaType::class, [
                'attr' => ['rows' => 6],
            ])
            ->add('save', SubmitType::class);
    }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
