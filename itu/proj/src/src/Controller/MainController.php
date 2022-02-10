<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Controller;

use App\Entity\Vehicle;
use App\Repository\ServiceRecordRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(VehicleRepository $vehicleRepository, ServiceRecordRepository $serviceRecordRepository): Response
    {
        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $vehicles = $vehicleRepository->findVehiclesByOwner($user);
            $services = $serviceRecordRepository->findAll($user);
            $date = date('Y-m-d', time());
            $dueDate = date('Y-m-d', strtotime($date . ' + 14 days'));


            return $this->render('main/index.html.twig', [
                'user' => $user,
                'vehicles' => $vehicles,
                'services' => $services,
                'date' => $date,
                'controller_name' => 'MainController',
            ]);
        } else {
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
            ]);
        }
    }
}
