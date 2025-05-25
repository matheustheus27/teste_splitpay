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
    #[OA\Response(
        response: 200,
        description: 'Lista todos os clientes',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Jose da Silva"),
                new OA\Property(property: 'email', type: 'string', example: "jose@teste.com"),
                new OA\Property(property: 'phone', type: 'string', example: "(31) 99999-9999"),
            ],
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
    )]
    public function index(ClientRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do cliente (CPF)',
        example: "11111111111"
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'path',
        required: true,
        description: 'Nome do cliente',
        example: "Jose da Silva"
    )]
    #[OA\Parameter(
        name: 'email',
        in: 'path',
        required: false,
        description: 'Email do cliente',
        example: "jose@teste.com"
    )]
    #[OA\Parameter(
        name: 'phone',
        in: 'path',
        required: false,
        description: 'Telefone do cliente',
        example: "(31) 99999-9999"
    )]
    #[OA\Response(
        response: 201,
        description: 'Retorna as informações do cliente',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Jose da Silva"),
                new OA\Property(property: 'email', type: 'string', example: "jose@teste.com"),
                new OA\Property(property: 'phone', type: 'string', example: "(31) 99999-9999"),
            ]
        )
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
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do cliente (CPF)',
        example: "11111111111"
    )]
    #[OA\Response(
        response: 200,
        description: 'Retorna as informações de um cliente',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Jose da Silva"),
                new OA\Property(property: 'email', type: 'string', example: "jose@teste.com"),
                new OA\Property(property: 'phone', type: 'string', example: "(31) 99999-9999"),
            ]
        )
    )]
    public function show(Client $client): JsonResponse
    {
        return $this->json($client);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do cliente (CPF)',
        example: "11111111111"
    )]
    #[OA\Parameter(
        name: 'name',
        in: 'path',
        required: false,
        description: 'Nome do cliente',
        example: "Jose da Silva"
    )]
    #[OA\Parameter(
        name: 'email',
        in: 'path',
        required: false,
        description: 'Email do cliente',
        example: "jose@teste.com"
    )]
    #[OA\Parameter(
        name: 'phone',
        in: 'path',
        required: false,
        description: 'Telefone do cliente',
        example: "(31) 99999-9999"
    )]
    #[OA\Response(
        response: 200,
        description: 'Retorna as informações do cliente',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'id', type: 'string', example: "11111111111"),
                new OA\Property(property: 'name', type: 'string', example: "Jose da Silva"),
                new OA\Property(property: 'email', type: 'string', example: "jose@teste.com"),
                new OA\Property(property: 'phone', type: 'string', example: "(31) 99999-9999"),
            ]
        )
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
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        required: true,
        description: 'ID do cliente (CPF)',
        example: "11111111111"
    )]
    #[OA\Response(
        response: 204,
        description: 'Retorna um json vazio',
    )]
    public function delete(Client $client, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($client);
        $em->flush();

        return $this->json(null, 204);
    }
}
