<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogueController extends AbstractController
{
    #[Route('/catalogue', name: 'catalogue')]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('catalogue/index.html.twig', [
            'title' => 'Articles',
            'pageTitle' => 'Nos Articles',
            'articles' => $articleRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/catalogue/{id}', name: 'detail')]
    public function detail(string $id, ManagerRegistry $doctrine): Response
    {

        $repository = $doctrine->getRepository(Article::class);
        $article = $repository->find($id);

        return $this->render('catalogue/detail.html.twig', [
            'title' => '',
            'pageTitle' => $article->getName(),
            'article' => $article
        ]);
    }
}
