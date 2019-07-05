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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends AbstractController
{
    private $em;
    private $encoder;
    private $repository;
    private $mailer;

    public function __construct(ObjectManager $em, UserPasswordEncoderInterface $encoder, UserRepository $repository, \Swift_Mailer $mailer) {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->repository = $repository;
        $this->mailer = $mailer;
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
            return $this->redirectToRoute('app_login');
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
    public function profile(int $id): Response
    {
        $user = $this->repository->find($id);

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'topics' => $user->getTopics()
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user.edit")
     * @param Request $request
     * @return void
     */
    public function edit(Request $request, int $id)
    {
        $user = $this->repository->find($id);

        $form = $this->createForm(UserType::class, $user);
        $form->remove('roles');
        $form->remove('password');
        $form->add('submit', SubmitType::class, ['label' => 'Edit']);
        $form->add('reset', SubmitType::class, ['label' => 'reset', 'attr' => ['class' => 'btn btn-secondary']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('reset')->isClicked()) {
                $this->resetPassword($form->getData());
                
                $this->addFlash('success', 'An email send to '.$user->getEmail());

                return $this->redirectToRoute('profile.show', ['id' => $user->getId()]);
            }

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Profile sauved');

            return $this->redirectToRoute('profile.show', ['id' => $user->getId()]);            
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/reset/password", name="user.reset.password")
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function reset(Request $request, int $id)
    {
        $user = $this->repository->find($id);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            if ($request->get('code') == substr($user->getCode(),0,4) && time() < substr($user->getCode(),4)) {
                if (password_verify($request->get('old_password'),$user->getPassword())) {
                    $user->setPassword($this->encoder->encodePassword($user, $request->get('new_password_1')));
                    $user->setCode(null);
                    $this->em->persist($user);
                    $this->em->flush();
                    return $this->redirectToRoute('app_login');
                }
            }
        }

        // dd('nop');

        return $this->render('user/reset_password.html.twig', [
            'user' => $user
        ]);
    }

    private function resetPassword(User $user)
    {
        $code = substr(rand(),0,4);
        $user->setCode($code.(time() + 1800));
        $this->em->persist($user);
        $this->em->flush();

        $message = (new \Swift_Message('Reset password'))
        ->setFrom('send@example.com')
        ->setTo($user->getEmail())
        ->setBody(
            $this->renderView(
                'emails/reset_password.html.twig',
                [
                    'code' => substr($code,0,4),
                    'user' => $user
                ]
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }
}
