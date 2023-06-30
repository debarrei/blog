<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/show-category/{id}', name: 'show_category', methods:['GET'])]
    public function show(Category $category): Response
    {
        return new Response('Category with name ' . $category->getName());
    }

    #[Route('/new-category', name: 'new_category')]
    public function new(Request $request): Response
    {
        $data = $request->toArray();
        $name = $data['name'];

        $category  = new Category();
        $category->setName($name);
        $this->em->persist($category);
        $this->em->flush();

        return new Response('Created new category with name ' . $category->getName());
    }

    #[Route('/remove-category/{id}', name: 'remove_category', methods:['DELETE'])]
    public function remove(Category $category): JsonResponse
    {
        $this->em->remove($category);
        $this->em->flush();

        return new JsonResponse(["message"=>"deleted category!"]);
    }

    #[Route('/update-category/{id}', name: 'update_category', methods:['POST'])]
    public function update(Category $category, Request $request): JsonResponse
    {
        $data = $request->toArray();
        $category->setName($data['name']);
        $this->em->flush();

        return new JsonResponse(["message"=>"category modified!"]);
    }
}
