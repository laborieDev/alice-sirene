<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class, [
            'required' => true,
            'attr' => [
                'placeholder' => 'Rechercher une entreprise',
            ]
        ]);
    }

    public function getName()
    {
        return 'SearchCompanyType';
    }
}
