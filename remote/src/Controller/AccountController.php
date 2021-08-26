<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/account")
 */

class AccountController extends AbstractController
{
    /**
     * @Route("", name="app_account", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('account/show.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

     /**
     * @Route("/edit", name="app_edit_account", methods={"GET","PATCH"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function editAccount(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $form=$this->createForm(UserFormType::class, $user, [
            'method' => 'PATCH'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Account successfully updated');
            return $this->redirectToRoute('app_account');
        }
            return $this->render('account/edit.html.twig', [
            'editform'=> $form->createView()
            ]);
    }

    /**
     * @Route("/change-password", name="app_change_password", methods={"GET","PATCH"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordEncoder): Response
    {

        $user = $this->getUser();
        $form=$this->createForm(ChangePasswordFormType::class, null, [
            'method' => 'PATCH',
            'current_password_required'=>true
        ]);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->hashPassword($user, $form['plainPassword']->getData())
            );
          
            $em->flush();
    
            $this->addFlash('success', 'Password successfully updated');
    
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/change.password.html.twig', [
            'form'=> $form->createView()
            ]);
    }
}
