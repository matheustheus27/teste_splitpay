<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/payment', name: 'api_payment')]
final class PaymentController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(PaymentRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $payment = new Payment();

        $payment->setId($data['id']);
        $payment->setRevenueId($data['revenue_id']);
        $payment->setAmount($data['amount']);
        $payment->setMethod($data['method']);
        $payment->setDtPayment($data['dt_payment']);

        $em->persist($payment);
        $em->flush();

        return $this->json($payment, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Payment $payment): JsonResponse
    {
        return $this->json($payment);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Payment $payment, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $payment->setRevenueId($data['revenue_id']);
        $payment->setAmount($data['amount']);
        $payment->setMethod($data['method']);
        $payment->setDtPayment($data['dt_payment']);

        $em->flush();

        return $this->json($payment);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Payment $payment, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($payment);
        $em->flush();

        return $this->json(null, 204);
    }
}
