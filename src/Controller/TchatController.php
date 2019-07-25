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
    private $userRepo;

    public function __construct(ObjectManager $em, TchatRepository $repository, UserRepository $userRepo) {
        $this->repository = $repository;
        $this->em = $em;
        $this->userRepo = $userRepo;
    }
    /**
     * @Route("/tchat", name="tchat")
     */
    public function index(Request $request)
    {
        $user = $request->get('tchat_user');
        $message = $request->get('tchat_message');

        $tchat = new Tchat();
        $tchat->setMessage($message);
        $tchat->setUser($this->userRepo->find($user));

        $this->em->persist($tchat);
        $this->em->flush();
    
        return new Response();
    }

    /**
     * @Route("/tchat/messages", name="tchat.messages")
     */
    public function messages()
    {
        $messages = [];
        foreach ($this->repository->findBy([],['id' => 'desc'],20) as $value) {
            $messages[] = [
                'id' => $value->getId(),
                'content' => $value->getMessage(),
                'user' => $value->getUser()->getUsername()
            ];
        }

        return $this->json($messages);
        // return $this->render('tchat/messages.html.twig', [
        //     'tchats' => $this->repository->findBy([],['id' => 'desc'],20)
        // ]);
    }

    /**
     * @Route("/tchat/new", name="tchat.new")
     */
    public function new(Request $request)
    {
        if ($request->get('content')) {

            $tchat = new Tchat();
            $tchat->setMessage($request->get('content'));
            $tchat->setUser($this->userRepo->find($request->get('user')));

            $this->em->persist($tchat);
            $this->em->flush();

            $response = [
                'content' => $tchat->getMessage(),
                'user' => $tchat->getUser()->getUsername()
            ];
        } else {
            $response = 'ERREUR';
        }
        return $this->json($response);
    }
}
