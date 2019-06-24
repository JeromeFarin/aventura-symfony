<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class AdminCategoryController extends AbstractController
{
    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em, CategoryRepository $categoryRepository) {
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/admin/category", name="admin.category.index")
     */
    public function categoryIndex(Request $request)
    {        
        $form = $this->createForm(CategoryType::class, new Category());
        $form->add('submit',SubmitType::class,['label' => 'Create']);
        $form->add('name',TextType::class,['label' => false,'attr' => ['placeholder' => 'Category name']]);
        $form->add('level',IntegerType::class,['label' => false,'attr' => ['placeholder' => 'Category level access']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/category/index.html.twig', [
            'title' => 'Aventura',
            'categories' => $this->categoryRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/{id}/edit", name="admin.category.edit")
     */
    public function categoryEdit(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->add('submit', SubmitType::class, ['label' => 'Edit']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();
            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/edit.html.twig', [
            'title' => 'Aventura',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/{id}/remove", name="admin.category.remove")
     */
    public function categoryRemove(Request $request, Category $category)
    {
        if (!$category) {
            throw new \Exception("No user Found");
        }
        $this->em->remove($category);
        $this->em->flush();
        return $this->redirectToRoute('admin.category.index');
    }
}
