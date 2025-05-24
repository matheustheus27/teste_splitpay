<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/service', name: 'api_service')]
final class ServiceController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ServiceRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $service = new Service();

        $service->setId($data['id']);
        $service->setName($data['name']);
        $service->setPrice($data['price']);

        $em->persist($service);
        $em->flush();

        return $this->json($service, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Service $service): JsonResponse
    {
        return $this->json($service);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Service $service, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $service->setName($data['name']);
        $service->setPrice($data['price']);

        $em->flush();

        return $this->json($service);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Service $service, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($service);
        $em->flush();

        return $this->json(null, 204);
    }
}
