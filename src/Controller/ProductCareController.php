<?php

namespace App\Controller;

use App\Entity\ProductCare;
use App\Repository\ProductCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/product/care', name: 'api_product_care')]
#[OA\Tag(name: 'Atentdimento Produto')]
final class ProductCareController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/product/care',
        summary: 'Lista todos os atendimentos produto',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos atendimentos produto',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'care_id', type: 'integer', example: 10),
                            new OA\Property(property: 'product_id', type: 'integer', example: 20),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_product_care_example',
                            summary: 'Exemplo de múltiplos atendimentos produto',
                            value: [ 
                                [
                                    'care_id' => 10,
                                    'product_id' => 20,
                                ],
                                [
                                    'care_id' => 11,
                                    'product_id' => 20,
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(ProductCareRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/product/care',
        summary: 'Cria um novo atendimento produto',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['care_id', 'product_id'],
                properties: [
                    new OA\Property(property: 'care_id', type: 'integer', example: 10),
                    new OA\Property(property: 'product_id', type: 'integer', example: 20),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do atendimento produto',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'care_id', type: 'integer', example: 10),
                        new OA\Property(property: 'product_id', type: 'integer', example: 20),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $productCare = new ProductCare();

        $productCare->setCareId($data['care_id']);
        $productCare->setProductId($data['product_id']);

        $em->persist($productCare);
        $em->flush();

        return $this->json($productCare, 201);
    }

    #[Route('/{care_id}/{product_id}', methods: ['GET'])]
    #[OA\Get(
        path: '/api/product/care/{care_id}/{product_id}',
        summary: 'Busca um atendimento produto pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'care_id',
                in: 'path',
                required: true,
                description: 'ID do atendimento profissional',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            ),
            new OA\Parameter(
                name: 'product_id',
                in: 'path',
                required: true,
                description: 'ID do produto',
                schema: new OA\Schema(type: 'integer'),
                example: 20
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de um atendimento produto',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'care_id', type: 'integer', example: 10),
                        new OA\Property(property: 'product_id', type: 'integer', example: 20),
                    ]
                )
            )
        ]
    )]
    public function show(int $care_id, int $product_id, ProductCareRepository $repo): JsonResponse
    {
        $productCare = $repo->findOneBy([
            'care_id' => $care_id,
            'product_id' => $product_id
        ]);

        return $this->json($productCare);
    }

    #[Route('/{care_id}/{product_id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/product/care/{care_id}/{product_id}',
        summary: 'Remove um atendimento produto pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'care_id',
                in: 'path',
                required: true,
                description: 'ID do atendimento profissional',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            ),
            new OA\Parameter(
                name: 'product_id',
                in: 'path',
                required: true,
                description: 'ID do produto',
                schema: new OA\Schema(type: 'integer'),
                example: 20
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Pagamento removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(int $care_id, int $product_id, ProductCareRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $productCare = $repo->findOneBy([
            'care_id' => $care_id,
            'product_id' => $product_id
        ]);

        $em->remove($productCare);
        $em->flush();

        return $this->json(null, 204);
    }
}
