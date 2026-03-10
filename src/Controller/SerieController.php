<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SerieController extends AbstractController
{
    #[Route('/series', name: 'series_')]
    public function list(): Response
    {

        //TODO renvoyer la liste
        return $this->render('series/list.html.twig');
    }
    #[Route('/series/{id}', name: 'series_show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        dump($id);
        //TODO renvoyer le détail d'une liste
        return $this->render('series/show.html.twig');

    }
    #[Route('series/create', name: 'series_create', methods: ['POST','GET'])]
    public function create(): Response
    {
        //TODO création d'un formulaire de création
        return $this->render('series/create.html.twig');

    }

}

