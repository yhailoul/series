<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('', name: 'main_home')]
    public function home(): Response
    {

        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {
        $serie=['name'=>'Dragon Ball Z', "author"=>'Toriyama','nbEpisodes'=>291];
        $username ='<h1>attaque XSS</h1>';
        return $this->render('main/test.html.twig', [
            'mySerie' => $serie, 'username' => $username
        ]);

    }
}
