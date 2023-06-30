<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/show-user/{id}', name: 'show_user', methods:['GET'])]
    public function show(User $user): Response
    {
        return new Response('User with name ' . $user->getName());
    }

    #[Route('/new-user', name: 'new_user')]
    public function new(Request $request): Response
    {
        $data = $request->toArray();
        $name = $data['name'];

        $user  = new User();
        $user->setName($name);
        $this->em->persist($user);
        $this->em->flush();

        return new Response('Created new user with name ' . $user->getName());
    }

    #[Route('/remove-user/{id}', name: 'remove_user', methods:['DELETE'])]
    public function remove(User $user): JsonResponse
    {
        $this->em->remove($user);
        $this->em->flush();

        return new JsonResponse(["message"=>"deleted user!"]);
    }

    #[Route('/update-user/{id}', name: 'update_user', methods:['POST'])]
    public function update(User $user, Request $request): JsonResponse
    {
        $data = $request->toArray();
        $user->setName($data['name']);
        $this->em->flush();

        return new JsonResponse(["message"=>"user modified!"]);
    }
}
