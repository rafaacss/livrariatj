<?php

namespace App\Form;

use App\Entity\Assunto;
use App\Entity\Livro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssuntoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Descricao', TextType::class, [
                'label' => 'Descrição',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Digite uma descrição para o assuntoo do livro',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assunto::class,
        ]);
    }
}
