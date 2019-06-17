<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use App\Repository\SubjectRepository;

class IndexController extends AbstractController
{
    private $categoryRepository;
    private $subjectRepository;

    public function __construct(CategoryRepository $categoryRepository, SubjectRepository $subjectRepository) {
        $this->categoryRepository = $categoryRepository;
        $this->subjectRepository = $subjectRepository;
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
            'categories' => $this->categoryRepository->findAll(),
            'subjects' => $this->subjectRepository->findAll()
        ]);
    }
}
