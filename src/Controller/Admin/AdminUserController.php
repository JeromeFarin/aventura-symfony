<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;

class AdminUserController extends AbstractController
{
    private $userRepository;
    private $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }
    /**
     * @Route("/admin/user", name="admin.user")
     */
    public function user(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form);
            // $this->em->persist($user);
            // $this->em->flush();
            return $this->redirectToRoute('admin.user');
        }
        // dd($form,$form->getData());
        
        return $this->render('admin/user.html.twig', [
            'title' => 'Aventura',
            'users' => $this->userRepository->findAll(),
            'roles' => $this->roleRepository->findAll(),
            'form' => $form->createView()
        ]);
    }
}
