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
    #[OA\Response(
        response: 200,
        description: 'Lista todos os produtos',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
            ],
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
    )]
    public function index(ProductRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do produto',
        example: 10
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'path',
        required: true,
        description: 'Nome do produto',
        example: "Dipirona 1mg"
    )]
    #[OA\Parameter(
        name: 'price',
        in: 'path',
        required: true,
        description: 'Valor do produto',
        example: 15.30
    )]
    #[OA\Response(
        response: 201,
        description: 'Retorna as informações do produto',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
            ]
        )
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
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do produto',
        example: 10
    )]
    #[OA\Response(
        response: 200,
        description: 'Retorna as informações de um produto',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
            ]
        )
    )]
    public function show(Product $product): JsonResponse
    {
        return $this->json($product);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do produto',
        example: 10
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'path',
        required: false,
        description: 'Nome do produto',
        example: "Dipirona 1mg"
    )]
    #[OA\Parameter(
        name: 'price',
        in: 'path',
        required: false,
        description: 'Valor do produto',
        example: 15.30
    )]
    #[OA\Response(
        response: 200,
        description: 'Retorna as informações do produto',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'int', example: 10),
                new OA\Property(property: 'name', type: 'string', example: "Dipirona 1mg"),
                new OA\Property(property: 'price', type: 'number', format: 'float', example: 15.30),
            ]
        )
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
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do produto',
        example: 10
    )]
    #[OA\Response(
        response: 204,
        description: 'Retorna um json vazio',
    )]
    public function delete(Product $product, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($product);
        $em->flush();

        return $this->json(null, 204);
    }
}
