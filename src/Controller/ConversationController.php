<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ConversationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ConversationController extends AbstractController
{
    private $em;
    private $repository;

    public function __construct(ObjectManager $em, ConversationRepository $repository) {
        $this->em = $em;
        $this->repository = $repository;
    }
    /**
     * @Route("/conversation", name="conversation.index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ConversationType::class);
        $form->remove('message');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->addUser($this->getUser());
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('conversation.index');
        }

        return $this->render('conversation/index.html.twig', [
            'form' => $form->createView(),
            'conversations' => $this->repository->findAll()
        ]);
    }

    /**
     * @Route("/conversation/{id}", name="conversation.show")
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function show(Request $request, int $id)
    {
        $conversation = $this->repository->find($id);

        $form = $this->createForm(ConversationType::class);
        $form->remove('title');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->addUser($this->getUser());
            $form->getData()->setParent($conversation);
            // dd($form->getData());
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('conversation.index');
        }

        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/conversation/{id}/user", name="conversation.user")
     * @param Request $request
     * @return void
     */
    public function user(Request $request, int $id, UserRepository $userRepo)
    {
        $conversation = $this->repository->find($id);

        $users = [];
        
        foreach ($conversation->getUser() as $value) {
            $users[] = $value->getId();
        }

        $form = $this->createForm(ConversationType::class, $conversation);
        $form->remove('title');
        $form->remove('message');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($request->get('users'))) {
                foreach ($request->get('users') as $value) {
                    $form->getData()->addUser($userRepo->find($value));
                }
                $this->em->persist($form->getData());
                $this->em->flush();
            }

            return $this->redirectToRoute('conversation.show', ['id' => $id]);
        }

        return $this->render('conversation/user.html.twig', [
            'conversation' => $conversation,
            'users' => $userRepo->findAllNotInConv($users),
            'form' => $form->createView()
        ]);
    }
}
