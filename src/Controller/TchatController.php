<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TchatRepository;
use App\Form\TchatType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class TchatController extends AbstractController
{
    private $repository;
    private $em;

    public function __construct(ObjectManager $em,TchatRepository $repository) {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/tchat", name="tchat")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(TchatType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form->getData()->setUser($this->getUser());

            $this->em->persist($form->getData());
            $this->em->flush();
        }

        return $this->render('tchat/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tchat/messages", name="tchat.messages")
     */
    public function messages()
    {
        return $this->render('tchat/messages.html.twig', [
            'tchats' => $this->repository->findBy([],['id' => 'desc'])
        ]);
    }
}
