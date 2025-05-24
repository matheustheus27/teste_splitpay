<?php

namespace App\Controller;

use App\Entity\ServiceCare;
use App\Repository\ServiceCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/api/service/care', name: 'api_service_care')]
final class ServiceCareController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ServiceCareRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $serviceCare = new ServiceCare();

        $serviceCare->setCareId($data['care_id']);
        $serviceCare->setServiceId($data['service_id']);

        $em->persist($serviceCare);
        $em->flush();

        return $this->json($serviceCare, 201);
    }

    #[Route('/{careid}/{serviceid}', methods: ['GET'])]
    public function show(int $careId, int $serviceId, ServiceCareRepository $repo): JsonResponse
    {
        $serviceCare = $repo->findOneBy([
            'careId' => $careId,
            'serviceId' => $serviceId
        ]);

        return $this->json($serviceCare);
    }

    #[Route('/{careid}/{serviceid}', methods: ['DELETE'])]
    public function delete(int $careId, int $serviceId, ServiceCareRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $serviceCare = $repo->findOneBy([
            'careId' => $careId,
            'serviceId' => $serviceId
        ]);

        $em->remove($serviceCare);
        $em->flush();

        return $this->json(null, 204);
    }
}
