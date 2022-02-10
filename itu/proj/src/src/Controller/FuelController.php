<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Controller;

use App\Entity\FuelRecord;
use App\Entity\GasStation;
use App\Form\AddGasStationTypeFormType;
use App\Form\AddRefuelingFormType;
use App\Repository\FuelRecordRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FuelController extends AbstractController
{
    /**
     * @Route("/fuel/refuel/{id}", name="fuel_refuel")
     * @IsGranted("ROLE_USER")
     */
    public function refuelAdd(int $id, Request $request, VehicleRepository $vehicleRepository): Response
    {
        $record = new FuelRecord();

        $vehicle = $vehicleRepository->find($id);
        $form = $this->createForm(AddRefuelingFormType::class, $record);
        $form->handleRequest($request);

        $record->setVehicle($vehicle);
        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $entityManager->persist($record);
            $entityManager->flush();

            $mileage = $form["mileage"]->getData();

            if ($vehicle->getOdometer() < $mileage) {
                $UPDATE_MILEAGE = 'UPDATE `vehicle` SET `odometer` = \'' . $mileage . '\' WHERE `vehicle`.`id` = ' . $vehicle->getId() . ';';
                $statement = $entityManager->getConnection()->prepare($UPDATE_MILEAGE);
                $statement->execute();
            }

            return $this->redirectToRoute('vehicle_detail', ['id' => $vehicle->getId()]);
        }

        return $this->render('fuel/refuel.html.twig', [
            'vehicle' => $vehicle,
            'refuel' => $form->createView(),
        ]);
    }

    /**
     * @Route("/fuel/refuel/all/{id}", name="view_all_refuels")
     * @IsGranted("ROLE_USER")
     */
    public function viewAllRefuels(int $id, VehicleRepository $vehicleRepository, FuelRecordRepository $fuelRecordRepository): Response
    {

        $vehicle = $vehicleRepository->find($id);
        $refuels = $fuelRecordRepository->findByVehicleId($id);
        
        return $this->render('fuel/viewAll.html.twig', [
            'vehicle' => $vehicle,
            'refuels' => $refuels,
        ]);
    }

    # DODELAVKA
    /**
     * @Route("/fuel/refuel/delete/{id}", name="delete_refuel")
     * @IsGranted("ROLE_USER")
     */
    public function deletos(int $id, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $entityManager = $this->getDoctrine()->getManager();
            $DELETE_REFUEL_RECORD = 'DELETE FROM `fuel_record` WHERE `fuel_record`.`id` = ' . $id . ';';
            $statement = $entityManager->getConnection()->prepare($DELETE_REFUEL_RECORD);
            $statement->execute();
    
        return new JsonResponse('good');
      }else{
          return new JsonResponse('bad');
      }
    }

    # //DODELAVKA

    # DODELAVKA
    /**
     * @Route("/fuel/station/add?={name}", name="add_gas_station_ajax")
     * @IsGranted("ROLE_USER")
     */
    public function addGasStationAjax(string $name, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $entityManager = $this->getDoctrine()->getManager();
            $INSERT_STATION = 'INSERT INTO `gas_station` (`id`, `name`) VALUES (NULL, \''. $name .'\');';
            $statement = $entityManager->getConnection()->prepare($INSERT_STATION);
            $statement->execute();
    
        return new JsonResponse('good');
      }
      else{
          return new JsonResponse('bad');
      }
    }
    # //DODELAVKA
}
