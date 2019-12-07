<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Computer;
use App\Form\NewComputerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ComputerController extends AbstractController
{
    /**
     * @Route("/computer", name="computer")
     */
    public function index()
    {
        return $this->redirectToRoute('client_computers');
    }

    /**
     * @Route("/computer/add_computer", name="add_computer")
     */
    public function addComputer(Request $request)
    {
        $computer = new Computer();
        $form = $this->createForm(NewComputerType::class, $computer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $computer = $form->getData();
            $cRepo = $this->getDoctrine()->getRepository(Client::class);
            $client = $cRepo->findByUser($this->getUser());
            $computer->setClient($client);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($computer);
            $entityManager->flush();
            $this->addFlash('success', 'Pridėtas kompiuteris');
            return $this->redirectToRoute('client_computers');
        }
        return $this->render('computer/addComputer.html.twig', [
            'form' => $form->createView(),
            ]);
    }
}
