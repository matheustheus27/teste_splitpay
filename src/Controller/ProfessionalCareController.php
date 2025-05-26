<?php

namespace App\Controller;

use App\Entity\ProfessionalCare;
use App\Repository\ProfessionalCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/professional/care', name: 'api_professional_care')]
#[OA\Tag(name: 'Atentdimento Profissional')]
final class ProfessionalCareController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/professional/care',
        summary: 'Lista todos os atendimentos profissionais',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos os atendimentos profissionais',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 10),
                            new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                            new OA\Property(property: 'client_id', type: 'string', example: "11111111111"),
                            new OA\Property(property: 'professional_id', type: 'string', example: "33333333333"),
                            new OA\Property(property: 'dt_service', type: 'string', example: "2025-05-25"),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_professional_care_example',
                            summary: 'Exemplo de múltiplos atendimentos profissionais na resposta',
                            value: [ 
                                [
                                    'id' => 10,
                                    'revenue_id' => 20,
                                    'client_id' => "11111111111",
                                    'professional_id' => "33333333333",
                                    'dt_service' => "2025-05-24"
                                ],
                                [
                                    'id' => 11,
                                    'revenue_id' => 20,
                                    'client_id' => "22222222222",
                                    'professional_id' => "33333333333",
                                    'dt_service' => "2025-05-25"
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(ProfessionalCareRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/professional/care',
        summary: 'Cria um novo atendimento profissional',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'revenue_id', 'client_id', 'professional_id', 'dt_service'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 10),
                    new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                    new OA\Property(property: 'client_id', type: 'string', example: "11111111111"),
                    new OA\Property(property: 'professional_id', type: 'string', example: "33333333333"),
                    new OA\Property(property: 'dt_service', type: 'string', example: "2025-05-25"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do atendimento profissional',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 10),
                        new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                        new OA\Property(property: 'client_id', type: 'string', example: "11111111111"),
                        new OA\Property(property: 'professional_id', type: 'string', example: "33333333333"),
                        new OA\Property(property: 'dt_service', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
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
    #[OA\Get(
        path: '/api/professional/care/{id}',
        summary: 'Busca um atendimento profissional pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do atendimento profissional',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de um atendimento profissional',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 10),
                    new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                    new OA\Property(property: 'client_id', type: 'string', example: "11111111111"),
                    new OA\Property(property: 'professional_id', type: 'string', example: "33333333333"),
                    new OA\Property(property: 'dt_service', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
    public function show(ProfessionalCare $professionalCare): JsonResponse
    {
        return $this->json($professionalCare);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/professional/care/{id}',
        summary: 'Atualiza os dados de um atendimento profissional pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do atendimento profissional',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                    new OA\Property(property: 'client_id', type: 'string', example: "11111111111"),
                    new OA\Property(property: 'professional_id', type: 'string', example: "33333333333"),
                    new OA\Property(property: 'dt_service', type: 'string', example: "2025-05-25"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações atualizadas do atendimento profissional',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 10),
                        new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                        new OA\Property(property: 'client_id', type: 'string', example: "11111111111"),
                        new OA\Property(property: 'professional_id', type: 'string', example: "33333333333"),
                        new OA\Property(property: 'dt_service', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, ProfessionalCare $professionalCare, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data['revenue_id'])) $professionalCare->setRevenueId($data['revenue_id']);
        if(isset($data['client_id'])) $professionalCare->setClientId($data['client_id']);
        if(isset($data['professional_id'])) $professionalCare->setProfessionalId($data['professional_id']);
        if(isset($data['dt_service'])) $professionalCare->setDtService($data['dt_service']);

        $em->flush();

        return $this->json($professionalCare);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/professional/care/{id}',
        summary: 'Remove um atendimento profissional pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do atendimento profissional',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Atendimento profissional removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(ProfessionalCare $professionalCare, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($professionalCare);
        $em->flush();

        return $this->json(null, 204);
    }
}
