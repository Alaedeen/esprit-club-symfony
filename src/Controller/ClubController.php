<?php

namespace App\Controller;

use App\Entity\Club;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

class ClubController extends AbstractController
{
    /**
     * @Route("/club", name="club")
     */
    public function index()
    {
        return $this->render('club/index.html.twig', [
            'controller_name' => 'ClubController',
        ]);
    }    /**
 * @Route("/clubList", name="clubList")
 */
    public function readClub()
    {
        $repository = $this->getDoctrine()->getRepository(Club::class);
        $clubs = $repository->findAll();
        return $this->render('club/read.html.twig', [
            'clubs' => $clubs,
        ]);
    }
    /**
     * @Route("/deleteClub/{ref}", name="deleteClub")
     */
    public function deleteClub($ref)
    {
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Club::class)->find($ref);
        $em->remove($club);
        $em->flush();
        return $this->redirectToRoute("clubList");
    }


}
