<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CallApiService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController
{
    #[Route('/')]
    public function home(): Response
    {
        return $this->redirectToRoute("home");
    }

    #[Route('home', name: 'home')]
    public function index(CallApiService $callApiService): Response
    {
        $departement = $callApiService->getDepartement();

        $cp = $callApiService->getCommuneNameByDepartement("63");
        //dd($cp);
        // $_SESSION['panier'][] = 'truc';
        // dd($_SESSION);
        return $this->render('home/index.html.twig', [
            'title' => 'Home',
            'pageTitle' => "Bienvenue sur Sportshop"
        ]);
    }

    #[Route('cgv', name: 'cgv')]
    public function cgv(): Response
    {
        return $this->render('home/cgv.html.twig', [
            'title' => 'Home',
            'pageTitle' => "Les conditions générales de ventes"
        ]);
    }

    #[Route('mentions', name: 'mentions')]
    public function mentions(): Response
    {
        return $this->render('home/mentions.html.twig', [
            'title' => 'Home',
            'pageTitle' => "Mentions"
        ]);
    }

    #[Route('politiques', name: 'politiques')]
    public function politiques(): Response
    {
        return $this->render('home/politiques.html.twig', [
            'title' => 'Home',
            'pageTitle' => "Politiques générales de vente"
        ]);
    }

    #[Route('account', name: 'account')]
    public function account(): Response
    {

        return $this->render('account/index.html.twig', [
            'title' => 'Mon Compte',
            'pageTitle' => 'Mes infos'
        ]);
    }

    #[Route('panierUser', name: 'panierUser')]
    public function panier(Request $requestStack): Response
    {
        $session = $requestStack->getSession();
        $panier = $session->get('panier');


        return $this->render('account/panier.html.twig', [
            'title' => 'Mon Compte',
            'pageTitle' => 'Mon panier',
            'panier' => $panier
        ]);
    }

    #[Route('/changeFavColor', name: 'changeFavColor')]
    public function changeFavColor(ManagerRegistry $doctrine)
    {
        $currentUser = $this->getUser();
        $entityManager = $doctrine->getManager();
        $user = $entityManager->getRepository(User::class)->find($currentUser->getId());
        $user->setFavColor($_POST["color"]);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute("account");
    }
}
