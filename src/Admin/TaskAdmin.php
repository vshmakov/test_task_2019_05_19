<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\BooleanType;

final class TaskAdmin extends AbstractAdmin
{
    protected const  TYPE_BOOLEAN = 'boolean';
    protected const  TYPE_DATETIME = 'datetime';

    protected $datagridValues = [
        '_sort_order' => 'DESC',
    ];

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('isDone', BooleanType::class);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->addIdentifier('title')
            ->add('username')
            ->add('email')
            ->add('isDone', self::TYPE_BOOLEAN);
    }
}
