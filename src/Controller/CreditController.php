<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Credit;
use App\Entity\User;
use App\Form\CreditType;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CreditController extends AbstractController
{
    /**
     * @Route("client/add_credit", name="add_credit")
     */
    public function addCredit(Request $request)
    {
        $id = $request->query->get('id');
        $credit = new Credit();
        $form = $this->createForm(CreditType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $credit = $form->getData();
            $cRepo = $this->getDoctrine()->getRepository(Client::class);
            $uRepo = $this->getDoctrine()->getRepository(User::class);
            $crRepo = $this->getDoctrine()->getRepository(Credit::class);
            $user = $uRepo->find($id);
            $client = $cRepo->findByUser($user);
            $oldCredit = $crRepo->getCreditByClient($client);
            $entityManager = $this->getDoctrine()->getManager();
            if ($oldCredit == null)
            {
                $credit->setClient($client);
                $entityManager->persist($credit);

            } else {
                $oldCredit->setAmount($credit->getAmount() + $oldCredit->getAmount());
                $entityManager->persist($oldCredit);
            }
            $entityManager->flush();
            $this->addFlash('success', 'Sąskaita papildyta');
            return $this->redirectToRoute('client_payments');
        }

        return $this->render('credit/addCredit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
