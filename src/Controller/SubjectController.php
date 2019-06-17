<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SubjectRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;

class SubjectController extends AbstractController
{
    private $subjectRepository;
    private $postRepository;
    private $commentRepository;

    public function __construct(SubjectRepository $subjectRepository, PostRepository $postRepository, CommentRepository $commentRepository) {
        $this->subjectRepository = $subjectRepository;
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }
    /**
     * @Route("/subject/{id}", name="subject")
     */
    public function index($id)
    {
        $title = $this->subjectRepository->findOneBy(['id' => $id])->getName();
        $posts = $this->postRepository->findBy(['subject' => $id]);
        $comments= $this->commentRepository->findBy(['post' => $posts[0]->getId()]);
        return $this->render('subject/index.html.twig', [
            'title' => $title,
            'posts' => $posts,
            'comments' => $comments
        ]);
    }
}
