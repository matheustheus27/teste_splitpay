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
    #[OA\Response(
        response: 200,
        description: 'Lista todos os atendimentos profissionais',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'care_id', type: 'integer', example: 10),
                new OA\Property(property: 'product_id', type: 'integer', example: 20),
            ],
            examples: [
                new OA\Examples(
                    example: 'multiple_product_care_example',
                    summary: 'Exemplo de múltiplos atendimentos produtos',
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
    )]
    public function index(ProductCareRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Parameter(
        name: 'care_id',
        in: 'path',
        required: true,
        description: 'ID do atendimento profissional',
        example: 10
    )]
    #[OA\Parameter(
        name: 'product_id',
        in: 'path',
        required: true,
        description: 'ID do produto',
        example: 20
    )]
    #[OA\Response(
        response: 201,
        description: 'Retorna as informações do atendimento produto',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'care_id', type: 'integer', example: 10),
                new OA\Property(property: 'product_id', type: 'integer', example: 20),
            ]
        )
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
    #[OA\Parameter(
        name: 'care_id',
        in: 'path',
        required: true,
        description: 'ID do atendimento profissional',
        example: 10
    )]
    #[OA\Parameter(
        name: 'product_id',
        in: 'path',
        required: true,
        description: 'ID do produto',
        example: 20
    )]
    #[OA\Response(
        response: 201,
        description: 'Retorna as informações de um atendimento produto',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'care_id', type: 'integer', example: 10),
                new OA\Property(property: 'product_id', type: 'integer', example: 20),
            ]
        )
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
    #[OA\Parameter(
        name: 'care_id',
        in: 'path',
        required: true,
        description: 'ID do atendimento profissional',
        example: 10
    )]
    #[OA\Parameter(
        name: 'product_id',
        in: 'path',
        required: true,
        description: 'ID do produto',
        example: 20
    )]
    #[OA\Response(
        response: 204,
        description: 'Retorna um json vazio',
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
