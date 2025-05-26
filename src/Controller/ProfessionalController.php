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
    #[OA\Get(
        path: '/api/professional',
        summary: 'Lista todos os profissionais',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos os profissionais',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                            new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                            new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_professional_example',
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
            )
        ]
    )]
    public function index(ProfessionalRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/professional',
        summary: 'Cria um novo profissional',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'name', 'specialty'],
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                    new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                    new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do profissional',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                        new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                        new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
                    ]
                )
            )
        ]
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
    #[OA\Get(
        path: '/api/professional/{id}',
        summary: 'Busca um profissional pelo ID (CPF)',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do profissional (CPF)',
                schema: new OA\Schema(type: 'string'),
                example: '11111111111'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de um profissional',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                        new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                        new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
                    ]
                )
            )
        ]
    )]
    public function show(Professional $professional): JsonResponse
    {
        return $this->json($professional);
    }

    #[Route('/{id}', methods: ['PUT'])]
     #[OA\Put(
        path: '/api/professional/{id}',
        summary: 'Atualiza os dados de um profissional pelo ID (CPF)',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do profissional (CPF)',
                schema: new OA\Schema(type: 'string'),
                example: '11111111111'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                    new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações atualizadas do profissional',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                        new OA\Property(property: 'name', type: 'string', example: "Dr Guilherme de Paula"),
                        new OA\Property(property: 'specialty', type: 'string', example: "Clinico Geral"),
                    ]
                )
            )
        ]
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
    #[OA\Delete(
        path: '/api/professional/{id}',
        summary: 'Remove um profissional pelo ID (CPF)',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do profissional (CPF)',
                schema: new OA\Schema(type: 'string'),
                example: '11111111111'
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Profissional removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(Professional $professional, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($professional);
        $em->flush();

        return $this->json(null, 204);
    }
}
