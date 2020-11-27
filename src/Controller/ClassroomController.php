<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Form\SearchClassroomType;
use App\Form\SearchStudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ClassroomController extends AbstractController
{
    /**
     * @Route("/classroom", name="classroom")
     */
    public function index()
    {
        return $this->render('classroom/index.html.twig', [
            'controller_name' => 'ClassroomController',
        ]);
    }
    /**
     * @Route("/classroomList", name="classroomList")
     */
    public function readClassroom()
    {
        $repository = $this->getDoctrine()->getRepository(Classroom::class);
        $classrooms = $repository->findAll();
        return $this->render('classroom/read.html.twig', [
            'classrooms' => $classrooms,
        ]);
    }
    /**
     * @Route("/deleteClassroom/{id}", name="deleteClassroom")
     */
    public function deleteClassroom( $id)
    {
        $em = $this->getDoctrine()->getManager();
        $classe = $em->getRepository(Classroom::class)->find($id);
        $em->remove($classe);
        $em->flush();
        return $this->redirectToRoute("classroomList");
    }
    /**
     * @Route("/addClassroom", name="addClassroom")
     */
    public function addClassroom(Request $request)
    {
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class,
            $classroom);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();
            return $this->redirectToRoute('classroomList');
        }
 return $this->render('classroom/newClassroom.html.twig', [
     'f' => $form->createView(),
 ]);
    }
    /**
     * @Route("/updateClassroom/{id}", name="updateClassroom")
     */
    public function updateClassroom( $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $classe = $em->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ClassroomType::class,
            $classe);
        $form->add('Modifier', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            //$em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('classroomList');
        }
        $em->flush();
        return $this->render('classroom/updateClassroom.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    /**
     * @Route("/afficheClassroom", name="afficheClassroom")
     */
    public function afficheClassroom(Request $request)
    {
      $repository = $this->getDoctrine()->getManager();
        $classrooms = $repository->getRepository(Classroom::class)->findAll();
        $form = $this->createForm(SearchClassroomType::class);
        $form->add('recherche', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $classe = $form->getData()->getName();
            $em1 = $repository->getRepository(Classroom::class);
            $classrooms = $em1->findBy(['Name'=>$classe]);
        }
        return $this->render('classroom/affiche.html.twig', ['classrooms' => $classrooms,
            'f' => $form->createView()])
      ;
    }

}
