<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategoryType extends AbstractType
{
    private $repository;

    public function __construct(CategoryRepository $repository) {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = ['No parent' => null];
        foreach ($this->repository->findAll() as $value) {
            $categories[$value->getName()] = $value;
        }

        $builder
            ->add('name')
            ->add('submit', SubmitType::class)
            ->add('parent', ChoiceType::class, ['choices' => $categories]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
