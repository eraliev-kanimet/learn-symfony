<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    #[Route('/cars', name: 'cars')]
    public function index(CarRepository $carRepository): Response
    {
        $cars = [];

        /** @var Car $car */
        foreach ($carRepository->findAll() as $car) {
            $key = $car->getNumber() . ' ' . $car->getBrand() . ' ' . $car->getModel();

            if (!isset($cars[$key])) {
                $cars[$key] = 0;
            }

            $cars[$key]++;
        }

        return $this->render('index.html.twig', compact('cars'));
    }

    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        return parent::render('pages/cars/' . $view, $parameters, $response);
    }
}
