<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminMemberController extends AbstractController
{
    /**
     * Index member manage
     *
     * @Route("/admin", name="admin.member.index")
     * @return Response
     */
    public function index(): Response
    {
        return new Response('Member list');
    }
}
