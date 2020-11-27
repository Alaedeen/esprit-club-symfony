<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\SearchStudentType;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }
    /**
     * @Route("/studentList", name="studentList")
     */
    public function readStudent()
    {
        $repository = $this->getDoctrine()->getRepository(Student::class);
        $strudents = $repository->findAll();
        // order By mail
       // $studentByMail = $repository->orderByMail();
        return $this->render('student/read.html.twig', [
            'students' => $strudents,
        ]);
    }
    /**
     * @Route("/deleteStudent/{nsc}", name="deleteStudent")
     */
    public function deleteStudent($nsc)
    {
        $em = $this->getDoctrine()->getManager();
        $student = $em->getRepository(Student::class)->find($nsc);
        $em->remove($student);
        $em->flush();
        return $this->redirectToRoute("studentList");
    } /**
 * @Route("/addStudent", name="addStudent")
 */
    public function addStudent(Request $request)
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class,
            $student);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();
            return $this->redirectToRoute('studentList');
        }
        return $this->render('student/newStudent.html.twig', [
            'f' => $form->createView(),
        ]);
    }
    /**
     * @Route("/listStudent", name="listStudent")
     */
    public function listStudent(StudentRepository $repository)
    {
        //$repository = $this->getDoctrine()->getRepository(Student::class);
       // $strudents = $repository->findAll();
        // order By mail
        $studentByMail = $repository->orderByMail();
        return $this->render('student/readbymail.html.twig', [
            'students' => $studentByMail,
        ]);
    }
    /**
     * @Route("/listStudentComplexe", name="listStudentComplexe")
     */
    public function listStudentComplexe(StudentRepository $repository,Request $request)
    {
        //$repository = $this->getDoctrine()->getRepository(Student::class);
        $students = $repository->findAll();
        // order By mail
        $studentsByMail = $repository->orderByMail();
        //search
        $searchForm = $this->createForm(SearchStudentType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $nsc = $searchForm->getData()->getNsc();
            $resultOfSearch = $repository->searchStudent($nsc);
            return $this->render('student/searchStudent.html.twig', array(
                "resultOfSearch" => $resultOfSearch,
                "searchStudent" => $searchForm->createView()));
        }
        return $this->render('student/readComplexe.html.twig', array(
            "students" => $students,
            "studentsByMail" => $studentsByMail,
            "searchStudent" => $searchForm->createView()));
    }


}
