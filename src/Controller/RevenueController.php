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
    #[OA\Response(
        response: 200,
        description: 'Lista todas as receitas',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'dt_creation', type: 'string', example: "25/05/2025"),
            ],
            examples: [
                new OA\Examples(
                    example: 'multiple_payment_example',
                    summary: 'Exemplo de múltiplos pagamentos na resposta',
                    value: [ 
                        [
                            'id' => 10,
                            'dt_creation' => "25/05/2025"
                        ],
                        [
                            'id' => 11,
                            'dt_creation' => "24/05/2025"
                        ]
                    ]
                )
            ]
        )
    )]
    public function index(RevenueRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID da receita',
        example: 10
    )]
    #[OA\Parameter(
        name: 'dt_creation',
        in: 'path',
        required: true,
        description: 'Data da receita',
        example: "25/05/2025"
    )]
    #[OA\Response(
        response: 201,
        description: 'Retorna as informações da receita',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'dt_creation', type: 'string', example: "25/05/2025"),
            ]
        )
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
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID da receita',
        example: 10
    )]
    #[OA\Response(
        response: 200,
        description: 'Retorna as informações da receita',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'dt_creation', type: 'string', example: "25/05/2025"),
            ]
        )
    )]
    public function show(Revenue $revenue): JsonResponse
    {
        return $this->json($revenue);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID da receita',
        example: 10
    )]
    #[OA\Parameter(
        name: 'dt_creation',
        in: 'path',
        required: false,
        description: 'Data da receita',
        example: "25/05/2025"
    )]
    #[OA\Response(
        response: 201,
        description: 'Retorna as informações da receita',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'dt_creation', type: 'string', example: "25/05/2025"),
            ]
        )
    )]
    public function update(Request $request, Revenue $revenue, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data['dt_creation'])) $revenue->setDtCreation($data['dt_creation']);

        $em->flush();

        return $this->json($revenue);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID da receita',
        example: 10
    )]
    #[OA\Response(
        response: 204,
        description: 'Retorna um json vazio',
    )]
    public function delete(Revenue $revenue, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($revenue);
        $em->flush();

        return $this->json(null, 204);
    }
}
