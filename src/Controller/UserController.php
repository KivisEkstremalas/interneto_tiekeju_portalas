<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Provider;
use App\Form\ChangeProviderType;
use App\Form\ProviderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\ClientType;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        return $this->redirect('userList');
    }

    /**
     * @Route("/user/changeProvider", name="change_provider")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function changeProvider(Request $request)
    {
        $form = $this->createForm(ChangeProviderType::class);
        $form->handleRequest($request);
        $id = $request->query->get('id');
        $cRepo = $this->getDoctrine()->getRepository(Client::class);
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($id);
        $client = $cRepo->findByUser($user);

        if ($form->isSubmitted() && $form->isValid())
        {
            $provider = $form['provider']->getData();
            $client->setProvider($provider);
            $client->setContractName(null);
            $client->setContractSigned(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);
            $em->flush();
            $this->addFlash('success', 'Tiekėjas pakeistas');
            return $this->redirectToRoute('userList');
        }

        return $this->render('users/editProvider.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/users/userList", name="userList")
     * @return Response
     */
    public function getUserList()
    {
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $users = $uRepo->findAll();
        return $this->render('users/userList.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @Route("setActive", name="set_active")
     * @return RedirectResponse
     */
    public function setUserActive(Request $request)
    {
        $id = $request->query->get('id');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($id);
        $user->setActive(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $this->addFlash('success', 'Jūs sutikote su puslapio taisyklėmis');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/users/editUser", name="editUser")
     */
    public function editUser(Request $request) {
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $id = $request->query->get('id');
        $user = $uRepo->find($id);

        return $this->render('users/editUser.html.twig', [
            'id' => $id,
            'role' => $user->getRole()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/users/editToProvider", name="editToProvider")
     */
    public function editToProvider(Request $request) {
        $form = $this->createForm(ProviderType::class);
        $form->handleRequest($request);
        $id = $request->query->get('id');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $provider = $form->getData();
            if ($user->getRole() == 'ROLE_CLIENT') {
                $cRepo = $this->getDoctrine()->getRepository(Client::class);
                $client = $cRepo->findByUser($user);
                if ($client != null && $client->getUser() != null) {
                    $client->setUser(null);
                }
            }
            $user->setRole('ROLE_PROVIDER');
            $provider->setUser($user);
            $user->setProvider($provider);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($provider);
            $entityManager->flush();
            $this->addFlash('success', 'Rolė pakeista');
            return $this->redirectToRoute('userList');
        }
        return $this->render('/users/editToProvider.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }

    /**
     * @param Request $request
     * @Route("/users/editToAdmin", name="editToAdmin"))
     * @return RedirectResponse
     */
    public function editToAdmin(Request $request) {
        $id = $request->query->get('id');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($id);
        if ($user->getRole() == 'ROLE_CLIENT') {
            $cRepo = $this->getDoctrine()->getRepository(Client::class);
            $client = $cRepo->findByUser($user);
            if ($client != null && $client->getUser() != null) {
                $client->setUser(null);
            }
            if ($client != null && $client->getProvider() != null) {
                $user->setProvider(null);
            }
        } elseif ($user->getRole() == 'ROLE_PROVIDER') {
            if ($user->getProvider() != null) {
                $user->setProvider(null);
            }
        }
        $user->setRole('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('success', 'Rolė pakeistas');
        return $this->redirect('userList');
    }
    /**
     * @param Request $request
     * @Route("/users/editToClient", name="editToClient")
     * @return RedirectResponse|Response
     */
    public function editToClient(Request $request) {
        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);
        $id = $request->query->get('id');
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($id);
        if ($form->isSubmitted() && $form->isValid())
        {
            $client = $form->getData();
            if ($user->getRole() == 'ROLE_PROVIDER') {
                $user->setProvider(null);
            }
            $user->setRole('ROLE_CLIENT');
            $client->setUser($user);
            $client->setContractSigned(false);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();
            $this->addFlash('success', 'Rolė pakeistas');
            return $this->redirect('userList');
        }

        return $this->render('/users/editToClient.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }
}