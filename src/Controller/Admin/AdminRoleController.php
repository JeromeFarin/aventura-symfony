<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\RoleRepository;
use App\Entity\Role;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RoleType;

class AdminRoleController extends AbstractController
{
    private $em;
    private $repository;

    public function __construct(ObjectManager $em, RoleRepository $repository) {
        $this->em = $em;
        $this->repository = $repository;
    }
    /**
     * @Route("/admin/role", name="admin.role.index")
     */
    public function index(Request $request)
    {
        $form = $this->createForm(RoleType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            $this->addFlash('success', 'Role is created');

            return $this->redirectToRoute('admin.role.index');
        }

        return $this->render('admin/role/index.html.twig', [
            'roles' => $this->repository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/role/{id}/remove", name="admin.role.remove")
     */
    public function roleRemove(Request $request, Role $role)
    {
        if (!$role) {
            throw new \Exception("No Role Found");
        }
        $this->em->remove($role);
        $this->em->flush();

        $this->addFlash('success', 'Role is deleted');

        return $this->redirectToRoute('admin.role.index');
    }
}
