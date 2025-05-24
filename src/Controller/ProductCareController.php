<?php

namespace App\Controller;

use App\Entity\ProductCare;
use App\Repository\ProductCareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/product/care', name: 'api_product_care')]
final class ProductCareController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ProductCareRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
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

    #[Route('/{careid}/{productid}', methods: ['GET'])]
    public function show(int $careId, int $productId, ProductCareRepository $repo): JsonResponse
    {
        $productCare = $repo->findOneBy([
            'careId' => $careId,
            'productId' => $productId
        ]);

        return $this->json($productCare);
    }

    #[Route('/{careid}/{productid}', methods: ['DELETE'])]
    public function delete(int $careId, int $productId, ProductCareRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $productCare = $repo->findOneBy([
            'careId' => $careId,
            'productId' => $productId
        ]);

        $em->remove($productCare);
        $em->flush();

        return $this->json(null, 204);
    }
}
