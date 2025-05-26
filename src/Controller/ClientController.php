<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/client', name: 'api_client')]
#[OA\Tag(name: 'Clientes')]
final class ClientController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/client',
        summary: 'Lista todos os clientes',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos os clientes',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                            new OA\Property(property: 'name', type: 'string', example: "Jose da Silva"),
                            new OA\Property(property: 'email', type: 'string', example: "jose@teste.com"),
                            new OA\Property(property: 'phone', type: 'string', example: "(31) 99999-9999"),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_clients_example',
                            summary: 'Exemplo de múltiplos clientes na resposta',
                            value: [ 
                                [
                                    'id' => "11111111111",
                                    'name' => "Jose da Silva",
                                    'email' => "jose@teste.com",
                                    'phone' => "(31) 99999-9999"
                                ],
                                [
                                    'id' => "22222222222",
                                    'name' => "Maria Oliveira",
                                    'email' => "maria@teste.com",
                                    'phone' => "(31) 88888-8888"
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(ClientRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/client',
        summary: 'Cria um novo cliente',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'name'],
                properties: [
                    new OA\Property(property: 'id', type: 'string', example: '11111111111'),
                    new OA\Property(property: 'name', type: 'string', example: 'Jose da Silva'),
                    new OA\Property(property: 'email', type: 'string', example: 'jose@teste.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '(31) 99999-9999'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do cliente',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: '11111111111'),
                        new OA\Property(property: 'name', type: 'string', example: 'Jose da Silva'),
                        new OA\Property(property: 'email', type: 'string', example: 'jose@teste.com'),
                        new OA\Property(property: 'phone', type: 'string', example: '(31) 99999-9999'),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $client = new Client();

        $client->setId($data['id']);
        $client->setName($data['name']);
        if(isset($data['email'])) $client->setEmail($data['email']);
        if(isset($data['phone'])) $client->setPhone($data['phone']);

        $em->persist($client);
        $em->flush();

        return $this->json($client, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(
        path: '/api/client/{id}',
        summary: 'Busca um cliente pelo ID (CPF)',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do cliente (CPF)',
                schema: new OA\Schema(type: 'string'),
                example: '11111111111'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de um cliente',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: '11111111111'),
                        new OA\Property(property: 'name', type: 'string', example: 'Jose da Silva'),
                        new OA\Property(property: 'email', type: 'string', example: 'jose@teste.com'),
                        new OA\Property(property: 'phone', type: 'string', example: '(31) 99999-9999'),
                    ]
                )
            )
        ]
    )]
    public function show(Client $client): JsonResponse
    {
        return $this->json($client);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/client/{id}',
        summary: 'Atualiza os dados de um cliente pelo ID (CPF)',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do cliente (CPF)',
                schema: new OA\Schema(type: 'string'),
                example: '11111111111'
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Jose da Silva'),
                    new OA\Property(property: 'email', type: 'string', example: 'jose@teste.com'),
                    new OA\Property(property: 'phone', type: 'string', example: '(31) 99999-9999'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações atualizadas do cliente',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'string', example: '11111111111'),
                        new OA\Property(property: 'name', type: 'string', example: 'Jose da Silva'),
                        new OA\Property(property: 'email', type: 'string', example: 'jose@teste.com'),
                        new OA\Property(property: 'phone', type: 'string', example: '(31) 99999-9999'),
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Client $client, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data['name'])) $client->setName($data['name']);
        if(isset($data['email'])) $client->setEmail($data['email']);
        if(isset($data['phone'])) $client->setPhone($data['phone']);

        $em->flush();

        return $this->json($client);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/client/{id}',
        summary: 'Remove um cliente pelo ID (CPF)',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID do cliente (CPF)',
                schema: new OA\Schema(type: 'string'),
                example: '11111111111'
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Cliente removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(Client $client, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($client);
        $em->flush();

        return $this->json(null, 204);
    }
}
