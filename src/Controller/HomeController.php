<?php

namespace App\Controller;

use App\Repository\OwnerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('', name: 'home')]
    public function index(OwnerRepository $ownerRepository): Response
    {
        $owners = $ownerRepository->findAll();

        return $this->render('pages/index.html.twig', compact('owners'));
    }
}
