<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/show-article/{id}', name: 'show_article', methods:['GET'])]
    public function show(Article $article): Response
    {
        return new Response('Article with name ' . $article->getTitle());
    }


    #[Route('/new-article', name: 'new-article', methods:['POST'])]
    public function new(Request $request): Response
    {
        $data = $request->toArray();
        $title = $data['title'];
        $content = $data['content'];
        $userId = $data['user-id'];
        $categoryId = $data['category-id'];

        $user = $this->em->getRepository(User::class)->find($userId);
        $category = $this->em->getRepository(Category::class)->find($categoryId);
        $article = new Article();
        $article->setTitle($title);
        $article->setContent($content);
        $article->setUser($user);
        $article->addCategory($category);
        $this->em->persist($article);
        $this->em->flush();

        return new Response('New article with title ' . $article->getTitle() . ' created for  '. $article->getUser()->getName());
    }

    #[Route('/remove-article/{id}', name: 'remove_arrticle', methods:['DELETE'])]
    public function remove(Article $article): JsonResponse
    {
        $this->em->remove($article);
        $this->em->flush();

        return new JsonResponse(["message"=>"deleted article!"]);
    }

    #[Route('/update-article/{id}', name: 'update_article', methods:['POST'])]
    public function update(Article $article, Request $request): JsonResponse
    {
        $data = $request->toArray();
        $title = $data['title'];
        $content = $data['content'];
        $userId = $data['user-id'];
        $categoryId = $data['category-id'];

        $user = $this->em->getRepository(User::class)->find($userId);
        $category = $this->em->getRepository(Category::class)->find($categoryId);
        $article->setTitle($title);
        $article->setContent($content);
        $article->setUser($user);
        $article->addCategory($category);
        $this->em->flush();

        return new JsonResponse(["message"=>"article modified!"]);
    }
}
