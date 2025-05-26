<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/payment', name: 'api_payment')]
#[OA\Tag(name: 'Pagamentos')]
final class PaymentController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    #[OA\Get(
        path: '/api/payment',
        summary: 'Lista todos os pagamentos',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Lista todos os pagamentos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'int', example: 10),
                            new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                            new OA\Property(property: 'amount', type: 'number', format: 'float', example: 150.95),
                            new OA\Property(property: 'method', type: 'string', example: "PIX"),
                            new OA\Property(property: 'dt_payment', type: 'string', example: "25/05/2025"),
                        ]
                    ),
                    examples: [
                        new OA\Examples(
                            example: 'multiple_payment_example',
                            summary: 'Exemplo de múltiplos pagamentos na resposta',
                            value: [ 
                                [
                                    'id' => 10,
                                    'revenue_id' => 20,
                                    'amount' => 150.95,
                                    'method' => "PIX",
                                    'dt_payment' => "2025-05-24"
                                ],
                                [
                                    'id' => 11,
                                    'revenue_id' => 20,
                                    'amount' => 145.32,
                                    'method' => "DEBITO",
                                    'dt_payment' => "2025-05-25"
                                ]
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function index(PaymentRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    #[OA\Post(
        path: '/api/payment',
        summary: 'Cria um novo pagamento',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['id', 'revenue_id', 'amount', 'dt_payment'],
                properties: [
                    new OA\Property(property: 'id', type: 'int', example: 10),
                    new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                    new OA\Property(property: 'amount', type: 'number', format: 'float', example: 150.95),
                    new OA\Property(property: 'method', type: 'string', example: "PIX"),
                    new OA\Property(property: 'dt_payment', type: 'string', example: "2025-05-25"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Retorna as informações do pagamento',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 150.95),
                        new OA\Property(property: 'method', type: 'string', example: "PIX"),
                        new OA\Property(property: 'dt_payment', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $payment = new Payment();

        $payment->setId($data['id']);
        $payment->setRevenueId($data['revenue_id']);
        $payment->setAmount($data['amount']);
        if(isset($data['method'])) $payment->setMethod($data['method']);
        $payment->setDtPayment($data['dt_payment']);

        $em->persist($payment);
        $em->flush();

        return $this->json($payment, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Get(
        path: '/api/payment/{id}',
        summary: 'Busca um pagamento pelo ID',
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
                response: 200,
                description: 'Retorna as informações de um pagamento',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 150.95),
                        new OA\Property(property: 'method', type: 'string', example: "PIX"),
                        new OA\Property(property: 'dt_payment', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
    public function show(Payment $payment): JsonResponse
    {
        return $this->json($payment);
    }

    #[Route('/{id}', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/payment/{id}',
        summary: 'Atualiza os dados de um pagamento pelo ID',
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
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                    new OA\Property(property: 'amount', type: 'number', format: 'float', example: 150.95),
                    new OA\Property(property: 'method', type: 'string', example: "PIX"),
                    new OA\Property(property: 'dt_payment', type: 'string', example: "2025-05-25"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Retorna as informações atualizadas do pagamento',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'int', example: 10),
                        new OA\Property(property: 'revenue_id', type: 'integer', example: 20),
                        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 150.95),
                        new OA\Property(property: 'method', type: 'string', example: "PIX"),
                        new OA\Property(property: 'dt_payment', type: 'string', example: "2025-05-25"),
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Payment $payment, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(isset($data['revenue_id'])) $payment->setRevenueId($data['revenue_id']);
        if(isset($data['amount'])) $payment->setAmount($data['amount']);
        if(isset($data['method'])) $payment->setMethod($data['method']);
        if(isset($data['dt_payment'])) $payment->setDtPayment($data['dt_payment']);

        $em->flush();

        return $this->json($payment);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/payment/{id}',
        summary: 'Remove um pagamento pelo ID',
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
                description: 'Pagamento removido com sucesso (sem conteúdo)'
            )
        ]
    )]
    public function delete(Payment $payment, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($payment);
        $em->flush();

        return $this->json(null, 204);
    }
}
