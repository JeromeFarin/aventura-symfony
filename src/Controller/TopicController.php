<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TopicRepository;
use App\Form\TopicType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Topic;

class TopicController extends AbstractController
{
    private $topicRepository;
    private $em;

    public function __construct(TopicRepository $topicRepository, ObjectManager $em)
    {
        $this->topicRepository = $topicRepository;
        $this->em = $em;
    }

    /**
     * @Route("/topic/{id}", name="topic.show")
     */
    public function show(Request $request, Topic $topic)
    {
        $form = $this->createForm(TopicType::class);
        $form->remove('title');
        $form->add('content', TextareaType::class, ['label' => false, 'attr' => ['class' => 'ckeditor']]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $form->getData()->setUser($this->getUser());
            $form->getData()->setParent($topic);
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('topic.show', ['id' => $topic->getId()]);
        }

        return $this->render('topic/show.html.twig', [
            'topic' => $topic,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/topic/{id}/remove", name="topic.remove")
     * @param Request $request
     * @return void
     */
    public function remove(Request $request, Topic $topic)
    {
        $this->em->remove($topic);
        $this->em->flush();

        return $this->redirectToRoute('topic.show', ['id' => $topic->getParent()->getId()]);
    }

    /**
     * @Route("/topic/{id}/edit", name="topic.edit")
     * @param Request $request
     * @return void
     */
    public function edit(Request $request, Topic $topic)
    {

        $form = $this->createForm(TopicType::class, $topic);
        $form->remove('title');
        $form->add('content', TextareaType::class, ['label' => false, 'attr' => ['class' => 'ckeditor']]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return $this->redirectToRoute('topic.show', ['id' => $topic->getParent()->getId()]);
        }

        return $this->render('topic/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
