<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Panier;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\FileUploaderService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RequestStack;

#[Route('article')]
#[IsGranted("ROLE_ADMIN")]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'title' => 'Back Office',
            'pageTitle' => 'Liste des articles'
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploaderService $fileUploader): Response
    {

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            //Traitement image
            $file = $form->get('img')->getData();

            if ($file) {
                $brochureFileName = $fileUploader->upload($file);
                $article->setImg($brochureFileName);
            }
            //Fin du traitement de l'img
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'title' => 'Back Office',
            'pageTitle' => 'Ajouter un article'
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'title' => 'Back Office',
            'pageTitle' => $article->getName()
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, FileUploaderService $fileUploader): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Traitement image
            $file = $form->get('img')->getData();

            if ($file) {
                $brochureFileName = $fileUploader->upload($file);
                $article->setImg($brochureFileName);
            }
            //Fin du traitement de l'img
            $entityManager->flush();

            return $this->redirectToRoute('article', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'title' => 'Back Office',
            'pageTitle' => $article->getName()
        ]);
    }

    #[Route('/{id}', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article', [], Response::HTTP_SEE_OTHER);
    }

    //Ajouter au panier via la session
    #[Route('/catalogue/addPanier/{id}', name: 'addArticle')]
    public function addArticle(string $id, RequestStack $requestStack, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);
        $panier = new Panier();
        $panier->setUser($this->getUser());
        $panier->setDate(new DateTime());
        $panier->addArticle($article);
        //$serializedPanier = serialize($panier);
        $session = $requestStack->getSession();
        $session->set('panier', $panier);
        return $this->redirectToRoute('panierUser', [], Response::HTTP_SEE_OTHER);
    }
}
