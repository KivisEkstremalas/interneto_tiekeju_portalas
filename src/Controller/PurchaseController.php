<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\NewPurchaseType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchase", name="purchase")
     */
    public function index()
    {
        return $this->redirectToRoute('provider_purchases');
    }

    /**
     * @Route("/purchase/add", name="add_purchase")
     */
    public function addPurchase(Request $request)
    {
        $purchase = new Purchase();
        $form = $this->createForm(NewPurchaseType::class, $purchase);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $purchase = $form->getData();
            $purchase->setProvider($this->getUser()->getProvider());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($purchase);
            $entityManager->flush();
            $this->addFlash('success', 'Sukurtas pirkimas');
            return $this->redirectToRoute('provider_purchases');
        }
        return $this->render('purchase/addPurchase.html.twig', [
            'form' => $form->createView(),
            ]);
    }

}
