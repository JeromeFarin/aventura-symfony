<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\MemberType;
use App\Entity\Member;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MemberController extends AbstractController
{
    private $em;
    private $encoder;

    public function __construct(ObjectManager $em, UserPasswordEncoderInterface $encoder) {
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * Register page
     *
     * @Route("/register", name="register")
     * @return Response
     */
    public function register(Request $request): Response
    {
        $member = new Member();
        $member->setAvatar('default.png');
        $member->setLevel(1);
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // todo : check if username and mail is available
            $form->getData()->setPassword($this->encoder->encodePassword($form->getData(), $form->getData()->getPassword()));
            $this->em->persist($member);
            $this->em->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('member/register.html.twig',[
            'title' => 'Aventura - Register',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    // /**
    //  * @Route("/login", name="login")
    //  */
    // public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    // {
    //     // get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     // last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();
        
    //     return $this->render('member/login.html.twig', [
    //         'title' => 'Aventura - Login',
    //         'last_username' => $lastUsername, 
    //         'error' => $error
    //     ]);
    // }

    // /**
    //  * @Route("/logout")
    //  * @throws \RuntimeException
    //  */
    // public function logoutAction()
    // {
    //     throw new \RuntimeException('This should never be called directly.');
    // }
}
