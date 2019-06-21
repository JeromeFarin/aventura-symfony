<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\MemberRepository;

class AdminMemberController extends AbstractController
{
    private $memberRepository;

    public function __construct(MemberRepository $memberRepository) {
        $this->memberRepository = $memberRepository;
    }
    /**
     * Index member manage
     *
     * @Route("/admin/member", name="admin.member.index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/member.html.twig',[
            'title' => 'Aventura',
            'members' => $this->memberRepository->findAll()
        ]);
    }
}
