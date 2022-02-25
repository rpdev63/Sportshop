<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Brand;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la marque'])
            ->add('reference')
            ->add('description')
            ->add('price', IntegerType::class, ['label' => 'Prix ( en centimes )'])
            ->add('oldPrice', IntegerType::class, ['label' => 'Prix barré (en centimes)'])
            ->add('img', FileType::class, [
                'mapped' => false,
                'required' => false, 'label' => 'Image de l\'article',
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/jpg', 'image/png', 'image/jpeg', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez déposer un fichier au format jpg, jpeg, png ou gif'
                    ])
                ]
            ])
            ->add('rate', IntegerType::class, ['label' => 'Note'])
            ->add('isNew', CheckboxType::class, ['label' => 'Nouveau ?', 'required' => false])

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add(
                'brand',
                EntityType::class,
                [
                    'class' => Brand::class,
                    'choice_label' => 'name',
                    'label' => 'Marque'
                ],

            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
