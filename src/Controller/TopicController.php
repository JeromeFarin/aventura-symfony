<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TopicRepository;

class TopicController extends AbstractController
{
    private $topicRepository;

    public function __construct(TopicRepository $topicRepository) {
        $this->topicRepository = $topicRepository;
    }

    /**
     * @Route("/topic/{id}", name="topic.show")
     */
    public function show(Request $request, int $id)
    {
        return $this->render('topic/show.html.twig', [
            'topic' => $this->topicRepository->findOneBy(['id' => $id])
        ]);
    }
}
