<?php

namespace App\Controller;

use App\Entity\Serie;
use Doctrine\ORM\EntityManagerInterface;
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
    public function create(EntityManagerInterface $entityManager): Response
    {
        $serie= new Serie();
        $serie
        ->setBackdrop('backdrop.png')
        ->setDateCreated(new \DateTime())
        ->setFirstAirDate(new \DateTime('-6 year'))
        ->setName('Stargate SG1')
        ->setGenres('SF')
        ->setLastAirDate(new \DateTime('-3 month'))
        ->setPopularity(5000)
        ->setPoster('poster.png')
        ->setStatus('canceled')
        ->setTmdbId(12345)
        ->setVote(8);
        dump($serie);
        //methode pour enregistrer dans ma base de données
        $entityManager->persist($serie);
        $entityManager->flush();

        $serie->setName('Code Quantum');
        $entityManager->persist($serie);
        $entityManager->flush();

        $serie->setName('Code Quantum 2');
        $entityManager->persist($serie);
        $entityManager->flush();

        $entityManager->remove($serie);
        $entityManager->flush();



        return $this->render('series/create.html.twig');
    }

}

