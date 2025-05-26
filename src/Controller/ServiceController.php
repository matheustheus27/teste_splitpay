<?php

namespace App\Controller;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/service', name: 'api_service')]
#[OA\Tag(name: 'Serviços')]
final class ServiceController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/service',
        summary: 'Lista todos os serviços',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos os serviços',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'int', example: 10),
                            new OA\Property(property: 'name', type: 'string', example: "Consulta"),
                            new OA\Property(property: 'price', type: 'number', format: 'float', example: 120),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_service_example',
                            summary: 'Exemplo de múltiplos serviços na resposta',
                            value: [ 
                                [
                                    'id' => 10,
                                    'name' => "Consulta",
                                    'price' => 120,
                                ],
                                [
                                    'id' => 11,
                                    'name' => "Exame",
                                    'price' => 65.25,
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(ServiceRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/service',
        summary: 'Cria um novo serviço',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'name', 'price'],
                properties: [
                    new OA\Property(property: 'id', type: 'int', example: 10),
                    new OA\Property(property: 'name', type: 'string', example: "Consulta"),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 120),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do serviço',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'name', type: 'string', example: "Consulta"),
                        new OA\Property(property: 'price', type: 'number', format: 'float', example: 120),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $service = new Service();

        $service->setId($data['id']);
        $service->setName($data['name']);
        $service->setPrice($data['price']);

        $em->persist($service);
        $em->flush();

        return $this->json($service, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(
        path: '/api/service/{id}',
        summary: 'Busca um serviço pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do serviço',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de um serviço',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'name', type: 'string', example: "Consulta"),
                        new OA\Property(property: 'price', type: 'number', format: 'float', example: 120),
                    ]
                )
            )
        ]
    )]
    public function show(Service $service): JsonResponse
    {
        return $this->json($service);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/service/{id}',
        summary: 'Atualiza os dados de um serviço pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do serviço',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: "Consulta"),
                    new OA\Property(property: 'price', type: 'number', format: 'float', example: 120),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações atualizadas do serviço',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'name', type: 'string', example: "Consulta"),
                        new OA\Property(property: 'price', type: 'number', format: 'float', example: 120),
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Service $service, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data['name'])) $service->setName($data['name']);
        if(isset($data['price'])) $service->setPrice($data['price']);

        $em->flush();

        return $this->json($service);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/service/{id}',
        summary: 'Remove um serviço pelo ID',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do serviço',
                schema: new OA\Schema(type: 'integer'),
                example: 10
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Serviço removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(Service $service, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($service);
        $em->flush();

        return $this->json(null, 204);
    }
}
