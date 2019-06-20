<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Form\CommentType;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    private $postRepository;
    private $commentRepository;

    public function __construct(PostRepository $postRepository, CommentRepository $commentRepository) {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
    }
    /**
     * @Route("/post/{id}", name="post")
     */
    public function index(Request $request, $id)
    {
        $title = $this->postRepository->findOneBy(['id' => $id])->getName();
        $post = $this->postRepository->findOneBy(['id' => $id]);
        $comments = $this->commentRepository->findBy(['post' => $id]);

        // TODO : dateAt does not exist why, "_"
        // $form = $this->createForm(CommentType::class, new Comment());

        return $this->render('post/index.html.twig', [
            'title' => $title,
            'post' => $post,
            // 'form' => $form,
            'comments' => $comments
        ]);
    }
}
