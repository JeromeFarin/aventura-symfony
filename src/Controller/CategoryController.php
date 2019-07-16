<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use App\Form\TopicType;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Category;

class CategoryController extends AbstractController
{
    private $categoryRepository;
    private $topicRepository;
    private $em;

    public function __construct(CategoryRepository $categoryRepository, TopicRepository $topicRepository, ObjectManager $em)
    {
        $this->categoryRepository = $categoryRepository;
        $this->topicRepository = $topicRepository;
        $this->em = $em;
    }

    /**
     * @Route("/category/{id}", name="category.show")
     */
    public function show(Request $request, Category $category)
    {
        $form = $this->createForm(TopicType::class);
        $form->add('title', null, ['required' => true]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form->getData()->setCategory($category);
            $form->getData()->setUser($this->getUser());
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('category.show', ['id' => $category->getId()]);
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'topics' => $this->topicRepository->findBy(['category' => $category->getId()])
        ]);
    }
}
