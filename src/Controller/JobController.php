<?php

namespace App\Controller;

use App\Entity\PropertyClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Job;

class JobController extends AbstractController
{
    #[Route('/api/job', name: 'app_job')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $jobs = $doctrine->getRepository(Job::class)->findAll();
        $data = [];

        foreach ($jobs as $job) {
            // Fetch PropertyClass for each job
            $propertyClass = $job->getPropertyClass();

            $data[] = [
                'id' => $job->getId(),
                'summary' => $job->getSummary(),
                'description' => $job->getDescription(),
                'status' => $job->getStatus(),
                'raisedby' => $job->getRaisedby(),
                'createdon' => $job->getCreatedon() ? $job->getCreatedon()->format('Y-m-d H:i:s') : null,
                'property' => $propertyClass ? $propertyClass->getName() : null, // assuming getName() is your method in PropertyClass
            ];
        }
        return $this->json($data);
    }
    #[Route('/api/jobcreate/{propertyId}', name: 'job_create', methods:['post'])]
    public function create(ManagerRegistry $doctrine, Request $request, int $propertyId): JsonResponse
    {

        // Required fields
        $requiredFields = ['summary', 'description', 'raisedby'];
        foreach ($requiredFields as $field) {
            if (!$request->request->has($field)) {
                return $this->json(['message' => "Field '$field' is required"], JsonResponse::HTTP_BAD_REQUEST);
            }
        }

        $entityManager = $doctrine->getManager();

        $job = new Job();
        $job->setSummary($request->request->get('summary'));
        $job->setDescription($request->request->get('description'));
        $job->setRaisedby($request->request->get('raisedby'));
        $job->setStatus('open');
        $propertyClass = $doctrine->getRepository(PropertyClass::class)->find($propertyId);
        $job->setPropertyClass($propertyClass);
        $job->setCreatedon(new \DateTime());



        $entityManager->persist($job);
        $entityManager->flush();
        $data = [
            'id' => $job->getId(),
            'summary' => $job->getSummary(),
            'description' => $job->getDescription(),
            'status' => $job->getStatus(),
            'raisedby' => $job->getRaisedby(),
            'createdon' => $job->getCreatedon(),
        ];
        return $this->json($data);

    }
    #[Route('/api/jobupdate/{id}', name: 'job_update', methods:['post'])]
    public function update(ManagerRegistry $doctrine, Request $request,int $id): JsonResponse
    {

        // Required fields
        $requiredFields = ['summary', 'description'];
        foreach ($requiredFields as $field) {
            if (!$request->request->has($field)) {
                return $this->json(['message' => "Field '$field' is required"], JsonResponse::HTTP_BAD_REQUEST);
            }
        }
        $entityManager = $doctrine->getManager();

        $job = $doctrine
            ->getRepository(Job::class)
            ->find($id);
        $job->setSummary($request->request->get('summary'));
        $job->setDescription($request->request->get('description'));
//        $job->setRaisedby($request->request->get('raisedby')); //ignored since the instruction said, no validation

        $entityManager->persist($job);
        $entityManager->flush();
        $data = [
            'id' => $job->getId(),
            'summary' => $job->getSummary(),
            'description' => $job->getDescription(),
            'status' => $job->getStatus(),
            'raisedby' => $job->getRaisedby(),
            'createdon' => $job->getCreatedon(),
        ];
        return $this->json($data);

    }
    #[Route('/api/job/{id}', name: 'job_details', methods: ['get'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $job = $doctrine
            ->getRepository(Job::class)
            ->find($id);
        if($job){
            $propertyClass = $job->getPropertyClass();
            $rsp[] = [
                'id' => $job->getId(),
                'summary' => $job->getSummary(),
                'description' => $job->getDescription(),
                'status' => $job->getStatus(),
                'raisedby' => $job->getRaisedby(),
                'createdon' => $job->getCreatedon() ? $job->getCreatedon()->format('Y-m-d H:i:s') : null,
                'property' => $propertyClass ? $propertyClass->getName() : null, // assuming getName() is your method in PropertyClass
            ];
        }

        return $this->json($rsp ?? []);
    }
    #[Route('/api/job/{id}', name: 'job_delete', methods: ['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $job = $doctrine
            ->getRepository(Job::class)
            ->find($id);
        if(!$job){
            return $this->json([
                'msg' => 'Job Does not exist'
            ],404);

        }
        $entityManager->remove($job);
        $rsp = [
            'msg' => 'Job Deleted'
        ];

        return $this->json($rsp,200);
    }

}
