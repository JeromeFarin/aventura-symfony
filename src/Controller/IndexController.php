<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TchatType;
use Doctrine\Common\Persistence\ObjectManager;

class IndexController extends AbstractController
{
    private $categoryRepository;
    private $em;

    public function __construct(ObjectManager $em,CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
        $this->em = $em;
    }

    /**
     * Home page
     *
     * @Route("/", name="index")
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('index.html.twig',[
            'title' => 'Aventura',
            'categories' => $this->categoryRepository->findAll()
        ]);
    }
}
