<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Owner;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

class CarController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, EntityManagerInterface $em): Response
    {
        $car = new Car();
        $owner = new Owner();

        $form = $this->createFormBuilder([$car, $owner])
            ->add('brand', TextType::class)
            ->add('model', TextType::class)
            ->add('owner', TextType::class)
            ->add('submit', SubmitType::class, ['label' => 'Регистрировать'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $car->setNumber('KG-' . rand(10000, 99999));
            $car->setBrand($data['brand']);
            $car->setModel($data['model']);

            $owner->setName($data['owner']);
            $owner->setCar($car);

            $em->persist($car);
            $em->persist($owner);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/re-register', name: 're_register')]
    public function reRegister(Request $request, CarRepository $carRepository, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('number', TextType::class, [
                'constraints' => [
                    new Assert\Callback(function ($value, $context) use ($carRepository) {
                        $car = $carRepository->findOneBy(['number' => $value]);

                        if (!$car) {
                            $context->addViolation('Машины с номером "' . $value . '" не существует.');
                        }
                    }),
                ],
                'row_attr' => [
                    'class' => 'form'
                ],
            ])
            ->add('owner', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Перерегистрация'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $car = $carRepository->findOneBy(['number' => $data['number']]);

            if (!$car) {
                return new Response('Машина не найдена', 404);
            }

            $owner = new Owner();
            $owner->setName($data['owner']);
            $owner->setCar($car);

            $em->persist($owner);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('re-register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

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

    #[Route('/cars/{number}', name: 'show')]
    public function show($number, CarRepository $carRepository): Response
    {
        $car = $carRepository->findOneBy(['number' => $number]);

        if ($car instanceof Car) {
            $owners = $car->getOwners();

            return $this->render('show.html.twig', compact(['car', 'owners']));
        }

        return new Response('Машина не найдена', 404);
    }

    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        return parent::render('pages/cars/' . $view, $parameters, $response);
    }
}
