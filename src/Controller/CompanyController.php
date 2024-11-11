<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/company')]
final class CompanyController extends AbstractController{
    #[Route(name: 'app_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    #[IsGranted("ROLE_DIRIGEANT")]
#[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $company = new Company();
    $form = $this->createForm(CompanyType::class, $company);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $this->getUser();

        // Récupérer le mot de passe en clair depuis le formulaire
        $plainPassword = $form->get('plainPassword')->getData();
        
        // Créer une instance de NativePasswordHasher pour hasher le mot de passe sans l'interface UserPasswordHasher
        $passwordHasher = new NativePasswordHasher();
        $hashedPassword = $passwordHasher->hash($plainPassword);

        // Attribuer le mot de passe hashé à Company et lier User à Company
        $company->setPassword($hashedPassword);
        $user->setCompany($company);
        $user->setCompanyPassword($hashedPassword);

        // Enregistrer les entités dans la base de données
        $entityManager->persist($user);
        $entityManager->persist($company);
        $entityManager->flush();

        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('company/new.html.twig', [
        'company' => $company,
        'form' => $form,
    ]);
}

    #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        $user = $this->getUser();

        // Vérifie si l'utilisateur appartient à la même entreprise et si les mots de passe correspondent
        if ($user && $user->getCompany() === $company && $user->getCompanyPassword() === $company->getPassword()) {
            return $this->render('company/show.html.twig', [
                'company' => $company,
            ]);
        }
    }

    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
    }
}
