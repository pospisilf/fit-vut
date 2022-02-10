<?php
# Author: Filip Pospisil (xpospi0f)
namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Color;
use App\Entity\Engine;
use App\Entity\Model;
use App\Form\AddBrandTypeFormType;
use App\Form\AddColorFormType;
use App\Form\AddEngineTypeFormType;
use App\Form\AddModelTypeFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CarParametersController extends AbstractController
{

    /**
     * @Route("/vehicle/brand/add", name="add_brand")
     * @IsGranted("ROLE_USER")
     */
    public function addBrand(Request $request): Response
    {
        $record = new Brand();

        $form = $this->createForm(AddBrandTypeFormType::class, $record);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $entityManager->persist($record);
            $entityManager->flush();
            return $this->redirectToRoute('vehicle_add');
        }

        return $this->render('car_parameters/addBrand.html.twig', [
            'addBrand' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/color/add", name="add_color")
     * @IsGranted("ROLE_USER")
     */
    public function addColor(Request $request): Response
    {
        $record = new Color();

        $form = $this->createForm(AddColorFormType::class, $record);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $entityManager->persist($record);
            $entityManager->flush();
            return $this->redirectToRoute('vehicle_add');
        }

        return $this->render('car_parameters/addColor.html.twig', [
            'addColor' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/brand/model/add", name="add_model")
     * @IsGranted("ROLE_USER")
     */
    public function addModel(Request $request): Response
    {
        $record = new Model();

        $form = $this->createForm(AddModelTypeFormType::class, $record);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $entityManager->persist($record);
            $entityManager->flush();
            return $this->redirectToRoute('vehicle_add');
        }

        return $this->render('car_parameters/addModel.html.twig', [
            'addModel' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/engine/add", name="add_engine")
     * @IsGranted("ROLE_USER")
     */
    public function addEngine(Request $request): Response
    {
        $record = new Engine();

        $form = $this->createForm(AddEngineTypeFormType::class, $record);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $entityManager->persist($record);
            $entityManager->flush();
            return $this->redirectToRoute('vehicle_add');
        }

        return $this->render('car_parameters/addEngine.html.twig', [
            'addEngine' => $form->createView(),
        ]);
    }
}
