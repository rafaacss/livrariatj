<?php

namespace App\Form;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titulo')
            ->add('Editora')
            ->add('Edicao')
            ->add('AnoPublicacao')
            ->add('Valor')
            ->add('assuntos', EntityType::class, [
                'class' => Assunto::class,
                'choice_label' => 'Descricao',
                'choice_value' => 'codAs',
                'multiple' => true,
            ])
            ->add('autores', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'Nome',
                'choice_value' => 'CodAu',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livro::class,
        ]);
    }
}
