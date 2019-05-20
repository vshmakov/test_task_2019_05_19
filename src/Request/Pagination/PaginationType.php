<?php

declare(strict_types=1);

namespace App\Request\Pagination;

use App\Request\BaseApiType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class PaginationType extends BaseApiType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('page', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => Pagination::class,
        ]);
    }
}
