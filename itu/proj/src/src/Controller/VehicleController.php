<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\AddVehicleFormType;
use App\Repository\FuelRecordRepository;
use App\Repository\ServiceRecordRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle/", name="vehicle")
     * @IsGranted("ROLE_USER")
     */
    public function index(VehicleRepository $vehicleRepository): Response
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $vehicles = $vehicleRepository->findVehiclesByOwner($user);
        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles,
            'controller_name' => 'VehicleController',
        ]);
    }

    /**
     * @Route("/vehicle/detail/{id}", name="vehicle_detail")
     * @IsGranted("ROLE_USER")
     */
    public function vehicleDetail(int $id, VehicleRepository $vehicleRepository,  ServiceRecordRepository $serviceRecordRepository, FuelRecordRepository $fuelRecordRepository): Response
    {
        $vehicle = $vehicleRepository->find($id);
        $services = $serviceRecordRepository->findByVehicleIdMax3($id);
        $refuels = $fuelRecordRepository->findByVehicleIdMax3($id);

        $refuelsAll = $fuelRecordRepository->findByVehicleId($id);
        $fuel_cost = 0;
        foreach ($refuelsAll as $refuel) {
            $fuel_cost = $fuel_cost + $refuel->getPrice();
        }

        $serviceAll = $serviceRecordRepository->findByVehicleId($id);
        $service_cost = 0;
        foreach ($serviceAll as $service) {
            $service_cost = $service_cost + $service->getPrice();
        }

        $total_cost = $fuel_cost + $service_cost;

        return $this->render('vehicle/detail.html.twig', [
            'vehicle' => $vehicle,
            'services' => $services,
            'refuels' => $refuels,
            'fuel_cost' => $fuel_cost,
            'service_cost' => $service_cost,
            'total_cost' => $total_cost,
        ]);
    }

    /**
     * @Route("/vehicle/edit/{id}", name="vehicle_edit")
     * @IsGranted("ROLE_USER")
     */
    public function editVehicle(int $id, Request $request, VehicleRepository $vehicleRepository): Response
    {
        $vehicle = $vehicleRepository->find($id);

        $form = $this->createForm(AddVehicleFormType::class, $vehicle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $vehicle = $form->getData();
            $em->persist($vehicle);
            $em->flush();

            return $this->redirectToRoute('vehicle_detail', ['id' => $vehicle->getId()]);
        }

        return $this->render('vehicle/edit.html.twig', [
            'editVehicle' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/add", name="vehicle_add")
     * @IsGranted("ROLE_USER")
     */
    public function addVehicle(Request $request): Response
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $vehicle = new Vehicle();
        $vehicle->setOwner($user);
        $form = $this->createForm(AddVehicleFormType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();
            return $this->redirectToRoute('vehicle');
        }

        return $this->render('vehicle/add.html.twig', [
            'addVehicle' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/delete/{id}", name="vehicle_delete")
     * @IsGranted("ROLE_USER")
     */
    public function deleteVehicle(int $id, VehicleRepository $vehicleRepository): Response
    {
        $vehicle = $vehicleRepository->find($id);
        $vehicleId = $vehicle->getId();
        $entityManager = $this->getDoctrine()->getManager();

        //tankovaci zaznamy
        $DELETE_F = 'DELETE FROM `fuel_record` WHERE `vehicle_id` = ' . $vehicleId . ';';
        $statement = $entityManager->getConnection()->prepare($DELETE_F);
        $statement->execute();

        //servnisni zaznamy
        $DELETE_S = 'DELETE FROM `service_record` WHERE `vehicle_id` = ' . $vehicleId . ';';
        $statement = $entityManager->getConnection()->prepare($DELETE_S);
        $statement->execute();

        //vozidlo
        $entityManager->remove($vehicle);
        $entityManager->flush();

        return $this->redirectToRoute('vehicle');
    }
}
