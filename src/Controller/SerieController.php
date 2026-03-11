<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series', name: 'series_')]
final class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page'=>'\d+'])]
    public function list( SerieRepository $serieRepository, int $page=1): Response
    {
        //$series = $serieRepository -> findAll();
        //$series= $serieRepository->findBy([],['popularity' => 'DESC'],25, 25);
        //$series=$serieRepository->findOneBy([],['popularity' => 'DESC']);
        $nbseries= $serieRepository->count();
        $maxPage = ceil($nbseries/50);
        if($page < 1) {
            return $this->redirectToRoute('series_list', ['page' => 1]);
        }
        if($page>$maxPage){
            throw $this->createNotFoundException("you've gone too far, there are no pages left");
        }

        $series=$serieRepository->findBestSeries($page);
        return $this->render('series/list.html.twig',['series'=>$series,'currentPage'=>$page,'maxPage'=>$maxPage]);
    }
    #[Route('/detail/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id,SerieRepository $serieRepository): Response
    {

       $serie = $serieRepository->find($id);

        if(!$serie){
            throw $this->createNotFoundException('Oooops! The show '.$id.' not found');
        }
        return $this->render('series/show.html.twig', ['serie'=>$serie]);

    }
    #[Route('/create', name: 'create', methods: ['POST','GET'])]
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

