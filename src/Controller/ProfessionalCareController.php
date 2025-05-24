<?php

namespace App\Controller;

use App\Entity\ProfessionalCare;
use App\Repository\ProfessionalCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/professional/care', name: 'api_professional_care')]
final class ProfessionalCareController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ProfessionalCareRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $professionalCare = new ProfessionalCare();

        $professionalCare->setId($data['id']);
        $professionalCare->setRevenueId($data['revenue_id']);
        $professionalCare->setClientId($data['client_id']);
        $professionalCare->setProfessionalId($data['professional_id']);
        $professionalCare->setDtService($data['dt_service']);

        $em->persist($professionalCare);
        $em->flush();

        return $this->json($professionalCare, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(ProfessionalCare $professionalCare): JsonResponse
    {
        return $this->json($professionalCare);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, ProfessionalCare $professionalCare, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $professionalCare->setRevenueId($data['revenue_id']);
        $professionalCare->setClientId($data['client_id']);
        $professionalCare->setProfessionalId($data['professional_id']);
        $professionalCare->setDtService($data['dt_service']);

        $em->flush();

        return $this->json($professionalCare);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(ProfessionalCare $professionalCare, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($professionalCare);
        $em->flush();

        return $this->json(null, 204);
    }
}
