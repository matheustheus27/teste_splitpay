<?php

namespace App\Controller;

use App\Entity\Professional;
use App\Repository\ProfessionalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

 #[Route('/api/professional', name: 'api_professional')]
 #[OA\Tag(name: 'Profissionais')]
final class ProfessionalController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Lista todos os profissionais',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
            ],
            examples: [
                new OA\Examples(
                    example: 'multiple_profissionais_example',
                    summary: 'Exemplo de múltiplos profissionais na resposta',
                    value: [ 
                        [
                            'id' => "11111111111",
                            'name' => "Dr Guilherme de Paula",
                            'specialty' => "Clinico Geral",
                        ],
                        [
                            'id' => "22222222222",
                            'name' => "Dra Maria Alencar",
                            'specialty' => "Psquiatria",
                        ]
                    ]
                )
            ]
        )
    )]
    public function index(ProfessionalRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do profissional (CPF)',
        example: "11111111111"
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'path',
        required: true,
        description: 'Nome do profissional',
        example: "Dr Guilherme de Paula"
    )]
    #[OA\Parameter(
        name: 'specialty',
        in: 'path',
        required: true,
        description: 'Especialidade Profissional',
        example: "Clinico Geral"
    )]
    #[OA\Response(
        response: 201,
        description: 'Retorna as informações do profissional',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
            ]
        )
    )]
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
     #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do profissional (CPF)',
        example: "11111111111"
    )]
    #[OA\Response(
        response: 200,
        description: 'Retorna as informações de um profissioanl',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
            ]
        )
    )]
    public function show(Professional $professional): JsonResponse
    {
        return $this->json($professional);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do profissional (CPF)',
        example: "11111111111"
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'path',
        required: false,
        description: 'Nome do profissional',
        example: "Dr Guilherme de Paula"
    )]
    #[OA\Parameter(
        name: 'specialty',
        in: 'path',
        required: false,
        description: 'Especialidade Profissional',
        example: "Clinico Geral"
    )]
    #[OA\Response(
        response: 200,
        description: 'Retorna as informações do cliente',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
            ]
        )
    )]
    public function update(Request $request, Professional $professional, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $professional->setName($data['name']);
        $professional->setSpecialty($data['especialty']);

        $em->flush();

        return $this->json($professional);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do profissional (CPF)',
        example: "11111111111"
    )]
    #[OA\Response(
        response: 204,
        description: 'Retorna um json vazio',
    )]
    public function delete(Professional $professional, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($professional);
        $em->flush();

        return $this->json(null, 204);
    }
}
