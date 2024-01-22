<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use App\Service\FlashMessageHelperInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
	#[Route('/user', name: 'app_user')]
	public function index(): Response
	{
		return $this->render(
			'user/index.html.twig',
			[
				'controller_name' => 'UserController',
			]
		);
	}
	
	#[Route('/inscription', name: 'inscription', methods: ["GET", "POST"])]
	public function inscription(UserManagerInterface $umi, FlashMessageHelperInterface $fmhi, RequestStack $requestStack, Request $request, UserRepository $repository, EntityManagerInterface $entityManager): Response
	{
		if($this->isGranted('ROLE_USER'))
		{
			return $this->redirectToRoute('index_get');
		}
		
		$user = new User();

		$formUser = $this->createForm(
			UserType::class, $user,
			[
				'method' => 'POST',
				'action' => $this->generateURL('inscription')
			]
		);
		
		// Traitement du formulaire.
		$formUser->handleRequest($request);
		
		$erreur = "";
		
		if($formUser->isSubmitted() && $formUser->isValid())
		{
			$umi->processNewUser($user, $formUser["plainPassword"]->getData(), $formUser["profilePictureFile"]->getData());
			$entityManager->persist($user);
			$entityManager->flush();
			
			$flashBag = $requestStack->getSession()->getFlashBag();
			$flashBag->add("succes", "Inscription bien réalisée !");
			
			return $this->redirectToRoute('index_get');
		}
		else if($formUser->isSubmitted() && !$formUser->isValid())
		{
			$fmhi->addFormErrorsAsFlash($formUser);
			
			$erreur = $requestStack->getSession()->getFlashBag()->get("erreur")[0];
		}
		
		$label = [
			'login' => "Login :",
			'mdp' => "Mot de passe :",
			'adresseEmail' => "Adresse E-mail :",
			'pdp' => "Photo de profil :"
		];
		
		return $this->render(
			'user/inscription.html.twig',
			[
				'label' => $label,
				'formUser' => $formUser,
				'erreur' => $erreur
			]
		);
	}
	
	#[Route('/connexion', name: 'connexion', methods: ['GET', 'POST'])]
	public function connexion(AuthenticationUtils $authenticationUtils) : Response
	{
		if($this->isGranted('ROLE_USER'))
		{
			return $this->redirectToRoute('feed_get_post');
		}
		
		$lastUsername = $authenticationUtils->getLastUsername();
		
		return $this->render(
			'user/connexion.html.twig',
			[
				'lastUsername' => $lastUsername
			]
		);
	}
}
