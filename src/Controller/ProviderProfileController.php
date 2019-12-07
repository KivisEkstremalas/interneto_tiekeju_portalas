<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Purchase;
use App\Entity\Payment;
use App\Form\ReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProviderProfileController extends AbstractController
{
    /**
     * @Route("/provider/profile", name="provider_profile")
     */
    public function getReport(Request $request)
    {
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $date = $form->getData();
            $provider = $this->getUser()->getProvider();
            $puRepo = $this->getDoctrine()->getRepository(Purchase::class);
            $paRepo = $this->getDoctrine()->getRepository(Payment::class);

            $dateTill = date('Y-m-d', strtotime("+1 month", strtotime(($date['date'])->format('Y-m-d'))));

            $purchases = $puRepo->findByProviderForDate($provider, $date['date']->format('Y-m-d'), $dateTill);
            $totalPurchases = 0;
            if ($purchases != null) {
                foreach ($purchases as $purchase) {
                    $totalPurchases = $totalPurchases + $purchase->getAmount();
                }
            }

            $payments = $paRepo->findByProviderForDate($provider, $date['date']->format('Y-m-d'), $dateTill);
            $totalPayments = 0;
            if ($payments != null) {
                foreach ($payments as $payment) {
                    if ($payment->getPaid()) {
                        $totalPayments = $totalPayments + $payment->getAmount();
                    }
                }
            }
            $this->addFlash('success', 'Ataskaita sugeneruota');

            return $this->render('provider_profile/report.html.twig', [
                'purchases' => $purchases,
                'payments' => $payments,
                'totalPurchases' => $totalPurchases,
                'totalPayments' => $totalPayments,
                'difference' => $totalPayments - $totalPurchases,
            ]);
        }
        return $this->render('provider_profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/provider/payments", name="provider_payments")
     */
    public function getPayments()
    {
        $pRepo = $this->getDoctrine()->getRepository(Payment::class);
        $payments = $pRepo->findByProvider($this->getUser()->getProvider());

        $total = $pRepo->totalPaymentsByProvider($this->getUser()->getProvider());
        return $this->render('provider_profile/paymentList.html.twig', [
            'payments' => $payments,
            'total' => $total,
        ]);
    }
    /**
     * @Route("/provider/clients", name="provider_clients")
     */
    public function getClients()
    {
        $clientRepo = $this->getDoctrine()->getRepository(Client::class);
        // $pRepo = $this->getDoctrine()->getRepository(Provider::class);
        // $provider = $pRepo->findByUser($this->getUser());
        $provider = $this->getUser()->getProvider();
        if ($provider) {
            $clients = $clientRepo->findByProvider($provider);
        }

        return $this->render('provider_profile/clientList.html.twig', [
            'clients' => $clients,
        ]);
    }

    /**
     * @Route("/provider/purchases", name="provider_purchases")
     */
    public function getPurchases()
    {
        // $prRepo = $this->getDoctrine()->getRepository(Provider::class);
        $puRepo = $this->getDoctrine()->getRepository(Purchase::class);
        $provider = $this->getUser()->getProvider();
        $purchases = $puRepo->findByProvider($provider);
        $totalAmount = $puRepo->totalProviderPurchaseAmount($provider);
        return $this->render('provider_profile/purchaseList.html.twig', [
            'purchases' => $purchases,
            'total' => $totalAmount,
        ]);
    }
}
