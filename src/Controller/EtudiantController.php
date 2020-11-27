<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Form\SearchEtudiantType;
use App\Repository\EtudiantRepository;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtudiantController extends AbstractController
{
    /**
     * @Route("/etudiant", name="etudiant")
     */
    public function index(): Response
    {
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
    /**
     * @Route("/EtudiantList", name="EtudiantList")
     */
    public function read(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Etudiant::class);
        $etudiants= $repository->findAll();
        return $this->render('etudiant/read.html.twig', [
            'etudiants' => $etudiants,
        ]);
    }
    /**
     * @Route("/addEtudiant", name="addEtudiant")
     */
    public function addEtudiant(Request $request): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class,$etudiant);
        $form->add('save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
           // $repository = $this->getDoctrine()->getRepository(Etudiant::class);
            $em= $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();
            return $this->redirectToRoute("EtudiantList");
        }
        return $this->render('etudiant/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/updateEtudiant/{nsc}", name="updateEtudiant")
     */
    public function updateEtudiant($nsc, Request $request): Response
    {
        //$etudiant = new Etudiant();
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Etudiant::class);
        $etudiant = $repository->find($nsc);
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            // $repository = $this->getDoctrine()->getRepository(Etudiant::class);
            $em= $this->getDoctrine()->getManager();
            //$em->persist($etudiant);
            $em->flush();
            return $this->redirectToRoute("EtudiantList");
        }
       $em->flush();
        return $this->render('etudiant/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/deleteEtudiant/{nsc}", name="deleteEtudiant")
     */
    public function deleteEtudiant($nsc): Response
    {
        //$etudiant = new Etudiant();
        //$em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Etudiant::class);
        $etudiant = $repository->find($nsc);
        $em = $this->getDoctrine()->getManager();
        $em->remove($etudiant);
        $em->flush();
        return $this->redirectToRoute('EtudiantList');
    }
    /**
     * @Route("/searchEtudiant", name="searchEtudiant")
     */
    public function searchEtudiant(EtudiantRepository $repository,Request $request): Response
    {
        $all = $repository->findAll();

        $form = $this->createForm(SearchEtudiantType::class);
        $form->add('recherche',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $nsc = $form->getData()->getNsc();
            $etudiant = $repository->getByNsc($nsc);
            return $this->render('etudiant/search.html.twig',[
                'form'=>$form->createView(),
            'etudiants'=>$etudiant
            ]);
        }
        return $this->render('etudiant/search.html.twig',[
        'form'=>$form->createView(),
        'etudiants'=>$all
    ]);
    }
}
