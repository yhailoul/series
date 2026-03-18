<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utile\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(
        FileUploader $fileUploader,
        EntityManagerInterface $entityManager,
        Request $request): Response
    {
        $serie= new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        //extraction des données de la requête pour injection dans l'instance de l'entité ou classe modèle associée
        $serieForm->handleRequest($request);
        if($serieForm->isSubmitted() && $serieForm->isValid()){

            //annotation pour faire comprendre à l'IDE de comprendre quel type de fichier j'utilise.
            /**
             * @var UploadedFile $file
             */
            //récupère une information qui n'est pas directement mappée avec l'entité
            //récupère un file qu'on peut upload
            $file= $serieForm->get('backdrop')->getData();
            $serie->setBackdrop($fileUploader->upload($file,'images/backdrops',$serie->getName()));
            $newFileName =$serie->getName().'-'.uniqid().'.'.$file->guessExtension();
            $file->move('images/backdrops', $newFileName);
            $serie->setBackdrop($newFileName);

            //traitement des données
            //$serie->setDateCreated(new \DateTime()); géré dans l'entité avec appel automatique juste avant l'enregistrement
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success','Serie'.$serie->getName(). 'has been added');

            return $this->redirectToRoute('series_show', ['id'=>$serie->getId()]);
        }

        return $this->render('series/create.html.twig',['serieForm'=>$serieForm]);
    }
    #[Route('/update/{id}', name: 'update', methods: ['POST','GET'])]
    public function update(int $id, SerieRepository $repository, EntityManagerInterface$entityManager, Request $request): Response
    {
        $serie = $repository->find($id);
        //creation du formulaire associé à la série
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);
        if($serieForm->isSubmitted() && $serieForm->isValid()){
            $entityManager->persist($serie);
            $entityManager->flush();
            $this->addFlash('success','Serie'.$serie->getName(). 'has been updated');
            $this->redirectToRoute('series_show', ['id'=>$serie->getId()]);
        }

        return $this->render('series/update.html.twig',['serieForm'=>$serieForm]);
    }
    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['POST','GET'])]
    public function delete(int $id, SerieRepository $repository, EntityManagerInterface$entityManager, Request $request): Response
    {
        $serie = $repository->find($id);
        if(!$serie){
            throw $this->createNotFoundException('Oooops! The delete '.$id.' not found');
        }
        $entityManager->remove($serie);
        $entityManager->flush();
        $this->addFlash('success','Serie: '.$serie->getName(). ' has been deleted');
        return $this->redirectToRoute('series_list');
    }
}

