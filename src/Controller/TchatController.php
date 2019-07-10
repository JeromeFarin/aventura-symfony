<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TchatRepository;
use App\Form\TchatType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Tchat;
use App\Repository\UserRepository;

class TchatController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(ObjectManager $em, TchatRepository $repository) {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/tchat", name="tchat")
     */
    public function index(Request $request, UserRepository $userRepo)
    {
        $user = $request->get('tchat_user');
        $message = $request->get('tchat_message');

        $tchat = new Tchat();
        $tchat->setMessage($message);
        $tchat->setUser($userRepo->find($user));

        $this->em->persist($tchat);
        $this->em->flush();
    
        return new Response();
    }

    /**
     * @Route("/tchat/messages", name="tchat.messages")
     */
    public function messages()
    {
        return $this->render('tchat/messages.html.twig', [
            'tchats' => $this->repository->findBy([],['id' => 'desc'],20)
        ]);
    }
}
