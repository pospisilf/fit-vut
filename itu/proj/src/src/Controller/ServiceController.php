<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Controller;

use App\Entity\Category;
use App\Entity\ServiceOperation;
use App\Entity\ServiceRecord;
use App\Form\AddCategoryTypeFormType;
use App\Form\AddServiceFormyType;
use App\Form\AddServisOperationTypeFormType;
use App\Repository\ServiceRecordRepository;
use App\Repository\VehicleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="service")
     * @IsGranted("ROLE_USER")
     */
    public function docasne(): Response
    {
        return $this->redirectToRoute('main');
    }

    /**
     * @Route("/service/add/{id}", name="service_add")
     * @IsGranted("ROLE_USER")
     */
    public function index(int $id, Request $request, VehicleRepository $vehicleRepository): Response
    {
        $record = new ServiceRecord();

        $vehicle = $vehicleRepository->find($id);
        $record->setVehicle($vehicle);
        $entityManager = $this->getDoctrine()->getManager();


        $form = $this->createForm(AddServiceFormyType::class, $record);
        $form->handleRequest($request);

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

        return $this->render('service/addRecord.html.twig', [
            'vehicle' => $vehicle,
            'servisRecord' => $form->createView(),
        ]);
    }

    # DODELAVKA
    /**
     * @Route("/service/delete/{id}", name="delete_service_record")
     * @IsGranted("ROLE_USER")
     */
    public function deleteService(int $id, Request $request): Response
    {
        if($request->isXmlHttpRequest()){
            $entityManager = $this->getDoctrine()->getManager();
            $DELETE_SERVICE_RECORD = 'DELETE FROM `service_record` WHERE `service_record`.`id` = ' . $id . ';';
            $statement = $entityManager->getConnection()->prepare($DELETE_SERVICE_RECORD);
            $statement->execute();
    
        return new JsonResponse('good');
        }else{
            return new JsonResponse('bad');
        }
    }
    # DODELAVKA

    /**
     * @Route("/service/operation/add/{id}", name="add_service_operation")
     * @IsGranted("ROLE_USER")
     */
    public function addOperation(int $id, Request $request): Response
    {
        $record = new ServiceOperation();

        $form = $this->createForm(AddServisOperationTypeFormType::class, $record);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $entityManager->persist($record);
            $entityManager->flush();
            return $this->redirectToRoute('service_add', ['id' => $id]);
        }

        return $this->render('service/addNewOperation.html.twig', [
            'addNewOperation' => $form->createView(),
        ]);
    }

    /**
     * @Route("/service/category/add", name="add_service_category")
     * @IsGranted("ROLE_USER")
     */
    public function addCategory(Request $request): Response
    {
        $record = new Category;

        $form = $this->createForm(AddCategoryTypeFormType::class, $record);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {
            $entityManager->persist($record);
            $entityManager->flush();
            return $this->redirectToRoute('main');
        }

        return $this->render('service/addNewCategory.html.twig', [
            'addNewCategory' => $form->createView(),
        ]);
    }

    /**
     * @Route("/service/detail/{id}", name="service_detail")
     * @IsGranted("ROLE_USER")
     */
    public function vehicleDetail(int $id, ServiceRecordRepository $serviceRecordRepository): Response
    {
        $service = $serviceRecordRepository->find($id);
        return $this->render('service/detail.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * @Route("/service/all/{id}", name="view_all_services")
     * @IsGranted("ROLE_USER")
     */
    public function viewAllServices(int $id, VehicleRepository $vehicleRepository, ServiceRecordRepository $serviceRecordRepository): Response
    {
        $vehicle = $vehicleRepository->find($id);
        $services = $serviceRecordRepository->findByVehicleId($id);

        return $this->render('service/viewAll.html.twig', [
            'vehicle' => $vehicle,
            'services' => $services,
        ]);
    }
}
