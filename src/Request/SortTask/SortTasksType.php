<?php

declare(strict_types=1);

namespace App\Request\SortTask;

use App\Request\BaseApiType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SortTasksType extends BaseApiType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('order_by', ChoiceType::class, [
                'choices' => [
                    'Username' => 'username',
                    'E-mail' => 'email',
                ],
                'label' => 'Field',
                'property_path' => 'property',
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => SortTasks::class,
        ]);
    }
}
