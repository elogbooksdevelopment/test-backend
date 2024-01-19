<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PropertyClass;

class PropertyController extends AbstractController
{
    #[Route('/api/property', name: 'app_property')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
       $properties = $doctrine
           ->getRepository(PropertyClass::class)
           ->findAll();
       $data = [];
       foreach($properties as $property){
           $data[] = [
               'id' => $property->getId(),
               'name' => $property->getName(),
           ];
       }
       return $this->json($data);
    }

    #[Route('/api/property', name: 'property_create', methods:['post'])]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $property = new PropertyClass();
        $property->setName($request->request->get('name'));

        $entityManager->persist($property);
        $entityManager->flush();
        $data = [
            'id' => $property->getId(),
            'name' => $property->getName(),
        ];
        return $this->json($data);

    }
    #[Route('/api/property/{id}', name: 'property_details', methods: ['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $properties = $doctrine
            ->getRepository(PropertyClass::class)
            ->find($id);
        if($properties){
            $rsp = [
                'id'=>$properties->getId(),
                'name'=>$properties->getName(),
            ];
        }

        return $this->json($rsp ?? []);
    }
    #[Route('/api/property/{id}', name: 'property_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $properties = $doctrine
            ->getRepository(PropertyClass::class)
            ->find($id);
        if(!$properties){
            return $this->json([
                'msg' => 'Property Does not exist'
            ],404);

        }
        $entityManager->remove($properties);
        $rsp = [
        'msg' => 'Property Delted'
    ];

        return $this->json($rsp,200);
    }


}
