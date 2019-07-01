<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminCategoryController extends AbstractController
{
    private $categoryRepository;
    private $em;

    public function __construct(CategoryRepository $categoryRepository, ObjectManager $em) {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/category", name="admin.category.index")
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('admin.category.index');
        }
        
        return $this->render('admin/category/index.html.twig', [
            'title' => 'Aventura',
            'form' => $form->createView(),
            'categories' => $this->categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/category/{id}/remove", name="admin.category.remove")
     * @param Request $request
     * @return void
     */
    public function remove(Request $request, int $id)
    {
        $this->em->remove($this->categoryRepository->findOneBy(['id' => $id]));
        $this->em->flush();

        return $this->redirectToRoute('admin.category.index');
    }

    /**
     * @Route("/admin/category/{id}/edit", name="admin.category.edit")
     * @param Request $request
     * @return void
     */
    public function edit(Request $request, int $id)
    {
        
        $form = $this->createForm(CategoryType::class, $this->categoryRepository->findOneBy(['id' => $id]));

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
}
