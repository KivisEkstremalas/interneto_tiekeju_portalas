<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Payment;
use App\Entity\Computer;
use App\Entity\Credit;
use App\Form\ClientPaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ClientProfileController extends AbstractController
{
    /**
     * @Route("/client/profile", name="client_profile")
     */
    public function index()
    {
        return $this->render('client_profile/index.html.twig');
    }

    /**
     * @Route("/client/contract", name="client_contract")
     */
    public function getClient()
    {
        $cRepo = $this->getDoctrine()->getRepository(Client::class);
        $client = $cRepo->findByUser($this->getUser());

        return $this->render('client_profile/contract.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/client/payments", name="client_payments")
     */
    public function clientPayments()
    {
        $cRepo = $this->getDoctrine()->getRepository(Client::class);
        $client = $cRepo->findByUser($this->getUser());
        $pRepo = $this->getDoctrine()->getRepository(Payment::class);
        $payments = $pRepo->findByClient($client);
        $total = $pRepo->totalPaymentsByClient($client);
        $crRepo = $this->getDoctrine()->getRepository(Credit::class);
        $totalCredit = $crRepo->sumActiveByClient($client);
        if ($total == null) {
            $total = 0;
        }
        if ($totalCredit == null) {
            $totalCredit = 0;
        }
        return $this->render('client_profile/paymentList.html.twig', [
            'payments' => $payments,
            'total' => $total,
            'totalCredit' => $totalCredit,
        ]);
    }

    /**
     * @Route("/client/computers", name="client_computers")
     */
    public function clientComputers()
    {
        $cRepo = $this->getDoctrine()->getRepository(Client::class);
        $client = $cRepo->findByUser($this->getUser());
        $comRepo = $this->getDoctrine()->getRepository(Computer::class);
        $computers = $comRepo->findByClient($client);

        return $this->render('client_profile/computerList.html.twig', [
            'computers' => $computers
        ]);
    }

    /**
     * @Route("client/completePayment", name="client_complete_payment")
     */
    public function completePayment(Request $request)
    {
        $form = $this->createForm(ClientPaymentType::class);
        $form->handleRequest($request);
        $id = $request->query->get('id');
        if ($form->isSubmitted() && $form->isValid())
        {
            $amount = $form['amount']->getData();
            $pRepo = $this->getDoctrine()->getRepository(Payment::class);
            $payment = $pRepo->find($id);
            $clRepo = $this->getDoctrine()->getRepository(Client::class);
            $client = $clRepo->find($payment->getClient()->getId());
            if ($amount < $payment->getAmount()) {
                $cRepo = $this->getDoctrine()->getRepository(Credit::class);
                $credit = $cRepo->getCreditByClient($client);
                if ($credit != null) {
                    if ($amount + $credit->getAmount() >= $payment->getAmount())
                    {
                        $credit->setAmount($credit->getAmount()-($payment->getAmount() - $amount));
                        $payment->setPaid(1);
                        $entityMangager = $this->getDoctrine()->getManager();
                        $entityMangager->flush();
                        $this->addFlash('success', 'Mokėjimas atliktas');
                        return $this->redirectToRoute('client_payments');
                    } else {
                        $this->addFlash('error', 'Nepakankamos lėšos');
                        return $this->redirectToRoute('client_payments');
                    }
                } else {
                    $this->addFlash('error', 'Nepakankamos lėšos');
                    return $this->redirectToRoute('client_payments');
                }

            } else {
                $cRepo = $this->getDoctrine()->getRepository(Credit::class);
                $credit = $cRepo->getCreditByClient($client);
                if ($credit == null) {
                    $credit = new Credit();
                    $credit->setAmount($amount - $payment->getAmount());
                    $credit->setClient($client);
                    $payment->setPaid(1);
                    $entityMangager = $this->getDoctrine()->getManager();
                    $entityMangager->flush($credit);
                    $this->addFlash('success', 'Mokėjimas atliktas');
                    return $this->redirectToRoute('client_payments');
                } else {
                    $credit->setAmount($credit->getAmount() + $amount - $payment->getAmount());
                    $payment->setPaid(1);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush($credit);
                    $this->addFlash('success', 'Mokėjimas atliktas');
                    return $this->redirectToRoute('client_payments');
                }
            }
        }

        return $this->render('payment/completePayment.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
