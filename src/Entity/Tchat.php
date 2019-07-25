<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TchatRepository")
 */
class Tchat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tchats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function serializer()
    {
        $encoder    = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setIgnoredAttributes(array(
            'user'
        ));

        // The setCircularReferenceLimit() method of this normalizer sets the number 
        // of times it will serialize the same object 
        // before considering it a circular reference. Its default value is 1.
        // $normalizer->setCircularReferenceHandler(function ($object) {
        //     return $object->getName();
        // });

        $serializer = new Serializer(array($normalizer), array($encoder));
        return $serializer->serialize($this, 'json');
    }
}
