<?php

namespace App\Controller;

use App\Entity\Revenue;
use App\Repository\RevenueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/revenue', name: 'api_revenue')]
#[OA\Tag(name: 'Receitas')]
final class RevenueController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/revenue',
        summary: 'Lista todos as receitas',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos as receitas',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'int', example: 10),
                            new OA\Property(property: 'dt_creation', type: 'string', example: "2025-05-25"),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_revenue_example',
                            summary: 'Exemplo de múltiplas receitas na resposta',
                            value: [ 
                                [
                                    'id' => 10,
                                    'dt_creation' => "2025-05-24"
                                ],
                                [
                                    'id' => 11,
                                    'dt_creation' => "2025-05-25"
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(RevenueRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/revenue',
        summary: 'Cria uma nova receita',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'dt_creation'],
                properties: [
                    new OA\Property(property: 'id', type: 'int', example: 10),
                    new OA\Property(property: 'dt_creation', type: 'string', example: "2025-05-25"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações da receita',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'dt_creation', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
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
    #[OA\Get(
        path: '/api/revenue/{id}',
        summary: 'Busca uma receita pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID da receita',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de uma receita',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'dt_creation', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
    public function show(Revenue $revenue): JsonResponse
    {
        return $this->json($revenue);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/revenue/{id}',
        summary: 'Atualiza os dados de uma receita pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID da receita',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'dt_creation', type: 'string', example: "2025-05-25"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações atualizadas da receita',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'dt_creation', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Revenue $revenue, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data['dt_creation'])) $revenue->setDtCreation($data['dt_creation']);

        $em->flush();

        return $this->json($revenue);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/revenue/{id}',
        summary: 'Remove uma receita pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do pagamento',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Receita removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(Revenue $revenue, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($revenue);
        $em->flush();

        return $this->json(null, 204);
    }
}
