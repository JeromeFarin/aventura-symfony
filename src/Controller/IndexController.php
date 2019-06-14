<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * Home page
     *
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index.html.twig',[
            'title' => 'Aventura'
        ]);
    }
}
