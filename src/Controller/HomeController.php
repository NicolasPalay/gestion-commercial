<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted("ROLE_USER")]
class HomeController extends AbstractController
{
    #[Route('dashboard', name: 'index')]
    public function dashboard(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }
    #[Route('home', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}
