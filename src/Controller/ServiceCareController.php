<?php

namespace App\Controller;

use App\Entity\ServiceCare;
use App\Repository\ServiceCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

 #[Route('/api/service/care', name: 'api_service_care')]
 #[OA\Tag(name: 'Atentdimento Serviço')]
final class ServiceCareController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/service/care',
        summary: 'Lista todos os atendimentos serviço',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos atendimentos serviço',
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
                            example: 'multiple_service_care_example',
                            summary: 'Exemplo de múltiplos atendimentos serviço',
                            value: [ 
                                [
                                    'care_id' => 10,
                                    'service_id' => 20,
                                ],
                                [
                                    'care_id' => 11,
                                    'service_id' => 20,
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(ServiceCareRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/service/care',
        summary: 'Cria um novo atendimento serviço',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['care_id', 'service_id'],
                properties: [
                    new OA\Property(property: 'care_id', type: 'integer', example: 10),
                    new OA\Property(property: 'service_id', type: 'integer', example: 20),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do atendimento serviço',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'care_id', type: 'integer', example: 10),
                        new OA\Property(property: 'service_id', type: 'integer', example: 20),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $serviceCare = new ServiceCare();

        $serviceCare->setCareId($data['care_id']);
        $serviceCare->setServiceId($data['service_id']);

        $em->persist($serviceCare);
        $em->flush();

        return $this->json($serviceCare, 201);
    }

    #[Route('/{care_id}/{service_id}', methods: ['GET'])]
    #[OA\Get(
        path: '/api/service/care/{care_id}/{service_id}',
        summary: 'Busca um atendimento serviço pelo ID',
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
                name: 'service_id',
                in: 'path',
                required: true,
                description: 'ID do serviço',
                schema: new OA\Schema(type: 'integer'),
                example: 20
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações de um atendimento serviço',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'care_id', type: 'integer', example: 10),
                        new OA\Property(property: 'service_id', type: 'integer', example: 20),
                    ]
                )
            )
        ]
    )]
    public function show(int $care_id, int $service_id, ServiceCareRepository $repo): JsonResponse
    {
        $serviceCare = $repo->findOneBy([
            'care_id' => $care_id,
            'service_id' => $service_id
        ]);

        return $this->json($serviceCare);
    }

    #[Route('/{care_id}/{service_id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/service/care/{care_id}/{service_id}',
        summary: 'Remove um atendimento serviço pelo ID',
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
                name: 'service_id',
                in: 'path',
                required: true,
                description: 'ID do serviço',
                schema: new OA\Schema(type: 'integer'),
                example: 20
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Serviço removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(int $care_id, int $service_id, ServiceCareRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $serviceCare = $repo->findOneBy([
            'care_id' => $care_id,
            'service_id' => $service_id
        ]);

        $em->remove($serviceCare);
        $em->flush();

        return $this->json(null, 204);
    }
}
