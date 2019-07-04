<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\TopicRepository;

class UserController extends AbstractController
{
    private $em;
    private $encoder;
    private $repository;

    public function __construct(ObjectManager $em, UserPasswordEncoderInterface $encoder, UserRepository $repository) {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->repository = $repository;
    }

    /**
     * Register page
     *
     * @Route("/register", name="register")
     * @return Response
     */
    public function register(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData()->setPassword($this->encoder->encodePassword($form->getData(), $form->getData()->getPassword()));
            $this->em->persist($user);
            $this->em->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('security/register.html.twig',[
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

    /**
     * @Route("/profile/{id}", name="profile.show")
     */
    public function profile(int $id, TopicRepository $topic): Response
    {
        $user = $this->repository->find($id);

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'topics' => $user->getTopics()
        ]);
    }
}
