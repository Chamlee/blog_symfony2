<?php

namespace Chamlee\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text');
        $builder->add('email', 'email');
        $builder->add('subject', 'text');
        $builder->add('body', 'textarea');
    }

    public function getName()
    {
        return 'contact';
    }
}