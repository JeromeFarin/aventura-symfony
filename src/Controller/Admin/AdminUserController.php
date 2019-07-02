<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\RoleRepository;

class AdminUserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @param UserRepository $userRepository
     * @param ObjectManager $em
     */
    public function __construct(UserRepository $userRepository, ObjectManager $em, RoleRepository $roleRepository) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/user", name="admin.user.index")
     */
    public function userIndex(Request $request)
    {        
        return $this->render('admin/user/index.html.twig', [
            'title' => 'Aventura',
            'users' => $this->userRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin.user.edit")
     */
    public function userEdit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->remove('password');
        $form->remove('roles');
        $form->add('submit', SubmitType::class, ['label' => 'Edit']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($request->request->get('roles'))) {
                $roles = [];
                foreach ($request->request->get('roles') as $value) {
                    $roles[] = $this->roleRepository->find($value)->getName();
                }
                $user->setRoles($roles);
            }
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('admin.user.index');
        }
        return $this->render('admin/user/edit.html.twig', [
            'title' => 'Aventura',
            'roles' => $this->roleRepository->findAll(),
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}/remove", name="admin.user.remove")
     */
    public function userRemove(Request $request, User $user)
    {
        if (!$user) {
            throw new \Exception("No user Found");
        }
        $this->em->remove($user);
        $this->em->flush();
        return $this->redirectToRoute('admin.user.index');
    }
}
