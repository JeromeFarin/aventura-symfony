<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;

class IndexController extends AbstractController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Home page
     *
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index.html.twig',[
            'title' => 'Aventura',
            'categories' => $this->categoryRepository->findAll()
        ]);
    }
}
