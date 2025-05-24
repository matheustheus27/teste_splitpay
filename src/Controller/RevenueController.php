<?php

namespace App\Controller;

use App\Entity\Revenue;
use App\Repository\RevenueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/revenue', name: 'api_revenue')]
final class RevenueController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(RevenueRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $revenue = new Revenue();

        $revenue->setId($data['id']);
        $revenue->setDtCreation($data['dt_creation']);

        $em->persist($revenue);
        $em->flush();

        return $this->json($revenue, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Revenue $revenue): JsonResponse
    {
        return $this->json($revenue);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Revenue $revenue, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $revenue->setDtCreation($data['dt_creation']);

        $em->flush();

        return $this->json($revenue);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Revenue $revenue, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($revenue);
        $em->flush();

        return $this->json(null, 204);
    }
}
