<?php

namespace App\Controller;

use App\Form\SavType;
use Mailjet\Resources;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SavController extends AbstractController
{
    #[Route('/sav', name: 'sav')]
    public function sav(Request $request): Response
    {
        //Formulaire de type contactType (qu'il faut créé)
        $form = $this->createForm(SavType::class);
        //récupère
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //On place le tout dans un tableau associatif
            $contactFormData = $form->getData();
            $this->sendMail("form3", $contactFormData);
        }

        return $this->render('home/contact.html.twig', [
            'form' => $form->createView(),
            'title' => '',
            'pageTitle' => 'Nous contacter'
        ]);
    }

    //Fonction pour envoyer des mails (composer require mailjet/mailjet-apiv3-php)
    public function sendMail(string $form, array $data)
    {
        $message = '';
        switch ($form) {
            case 'form1':
                $message = "<h2> {$data['civilite']} {$data['prenom']} {$data['nom']}, nous avons bien reçu votre message<h2> <br>
                            <p> Le voici : <p> 
                            <p> {$data['message']} </p>";
                break;

            case 'form2':
                $message = "<h2>Nous traitons votre problème : <h2>
                            <p> {$data['motif']} concernant la commande n° : {$data['numeroCommande']};</p>
                            <p> Votre message : </p>
                            <p> {$data['message']} </p>";

                break;

            case 'form3':
                $message = "<h2> {$data['civilite']} {$data['prenom']} {$data['nom']} Nous traitons votre problème : <h2>
                                <p> Motif : {$data['motif']} concernant la commande n° : {$data['numeroCommande']};</p>
                                <p> Votre message : </p>
                                <p> {$data['message']} </p>";

                break;

            default:
                # code...
                break;
        }

        $mj = new \Mailjet\Client('b83ecc69843edfafd6d6190da6f186e6', '51d5d579c65980b65f3ffaa60bd6d7b9', true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "rpdev63@protonmail.com",
                        'Name' => "Rémi"
                    ],
                    'To' => [
                        [
                            'Email' => $data['email'],
                            'Name' => "Rémi"
                        ]
                    ],
                    'Subject' => "Greetings from Mailjet.",
                    'TextPart' => "Salut comment vas-tu?",
                    'HTMLPart' => $message,
                    'CustomID' => "AppGettingStartedTest"
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);

        //&& var_dump($response->getData());
        if ($response->success()) {
            return $this->redirectToRoute("home");
        }
    }
}
