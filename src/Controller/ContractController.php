<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ContractType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ContractController extends AbstractController
{
    /**
     * @Route("/contract", name="contract")
     */
    public function index(Request $request)
    {
        $id = $request->query->get('id');
        $cRepo = $this->getDoctrine()->getRepository(Client::class);
        $client = $cRepo->find($id);
        $form = $this->createForm(ContractType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $contractFile = $form['contract']->getData();
            if ($contractFile)
            {
                $originalFilename = pathinfo(($client->getName().'_'.$client->getSurname()), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$contractFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $contractFile->move(
                        $this->getParameter('contracts_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Nepavyko įkelti sutarties');
                    return $this->redirectToRoute('provider_clients');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $client->setContractName($newFilename);
                $client->setContractSigned(false);

                $em = $this->getDoctrine()->getManager();
                $em->persist($client);
                $em->flush();
                $this->addFlash('success', 'Sutartis įkelta');
                return $this->redirectToRoute('provider_clients');
            }
        }
        return $this->render('contract/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/provider/deleteContract", name="delete_contract")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteContract(Request $request)
    {
        $id = $request->query->get('id');
        $cRepo = $this->getDoctrine()->getRepository(Client::class);
        $client = $cRepo->find($id);
        $client->setContractSigned(false);
        $client->setContractName(null);
        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();
        $this->addFlash('success', 'Sutartis ištrinta');
        return $this->redirectToRoute('provider_clients');
    }

    /**
     * @Route("/client/sign_contract", name="sign_contract")
     * @param Request $request
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function addSignedContract(Request $request)
    {
        $cRepo = $this->getDoctrine()->getRepository(Client::class);
        $client = $cRepo->findByUser($this->getUser());
        $form = $this->createForm(ContractType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $contractFile = $form['contract']->getData();
            if ($contractFile)
            {
                $originalFilename = pathinfo(($client->getName().'_'.$client->getSurname()), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$contractFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $contractFile->move(
                        $this->getParameter('contracts_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Nepavyko įkelti sutarties');
                    return $this->redirectToRoute('client_contract');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $client->setContractName($newFilename);
                $client->setContractSigned(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($client);
                $em->flush();
                $this->addFlash('success', 'Sutartis įkelta');
                return $this->redirectToRoute('client_profile');
            }
        }
        return $this->render('client_profile/signContract.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
