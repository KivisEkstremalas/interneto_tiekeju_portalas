<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Payment;
use App\Form\NewPaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index()
    {
        return $this->redirectToRoute('provider_payments');
    }

    /**
     * @Route("/payment/new", name="add_payment")
     */
    public function createPayment(Request $request)
    {
        $clientID = $request->query->get('clientID');
        $payment = new Payment();
        $form = $this->createForm(NewPaymentType::class, $payment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $cRepo = $this->getDoctrine()->getRepository(Client::class);
            $client = $cRepo->findById($clientID);
            $provider = $this->getUser()->getProvider();
            $payment = $form->getData();
            $payment->setClient($client);
            $payment->setProvider($provider);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment);
            $entityManager->flush();
            $this->addFlash('success', 'Sukurtas mokėjimas');
            return $this->redirectToRoute('provider_payments');
        }
        return $this->render('payment/addPayment.html.twig', [
            'form' => $form->createView(),
            ]);
    }
}
