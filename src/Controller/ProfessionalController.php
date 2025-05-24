<?php

namespace App\Controller;

use App\Entity\Professional;
use App\Repository\ProfessionalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

 #[Route('/api/professional', name: 'api_professional')]
final class ProfessionalController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ProfessionalRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $professional = new Professional();

        $professional->setId($data['id']);
        $professional->setName($data['name']);
        $professional->setSpecialty($data['specialty']);

        $em->persist($professional);
        $em->flush();

        return $this->json($professional, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Professional $professional): JsonResponse
    {
        return $this->json($professional);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Professional $professional, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $professional->setName($data['name']);
        $professional->setSpecialty($data['especialty']);

        $em->flush();

        return $this->json($professional);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Professional $professional, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($professional);
        $em->flush();

        return $this->json(null, 204);
    }
}
