<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/product', name: 'api_product')]
#[OA\Tag(name: 'Produtos')]
final class ProductController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/product',
        summary: 'Lista todos os produtos',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos os produtos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'int', example: 10),
                            new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                            new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_product_example',
                            summary: 'Exemplo de múltiplos produtos na resposta',
                            value: [ 
                                [
                                    'id' => 10,
                                    'name' => "Dipirona 1mg",
                                    'price' => 15.30,
                                ],
                                [
                                    'id' => 11,
                                    'name' => "Histamin 2mg",
                                    'price' => 11.99,
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(ProductRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/product',
        summary: 'Cria um novo serviço',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'name', 'price'],
                properties: [
                    new OA\Property(property: 'id', type: 'int', example: 10),
                    new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do produto',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                        new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $product = new Product();

        $product->setId($data['id']);
        $product->setName($data['name']);
        $product->setPrice($data['price']);

        $em->persist($product);
        $em->flush();

        return $this->json($product, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(
        path: '/api/product/{id}',
        summary: 'Busca um produto pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do produto',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de um produto',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                        new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
                    ]
                )
            )
        ]
    )]
    public function show(Product $product): JsonResponse
    {
        return $this->json($product);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/product/{id}',
        summary: 'Atualiza os dados de um produto pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do produto',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações atualizadas do produto',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                        new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Product $product, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data['name'])) $product->setName($data['name']);
        if(isset($data['price'])) $product->setPrice($data['price']);

        $em->flush();

        return $this->json($product);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/product/{id}',
        summary: 'Remove um produto pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do produto',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Produto removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($product);
        $em->flush();

        return $this->json(null, 204);
    }
}
