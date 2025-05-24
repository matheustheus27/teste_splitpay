<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client', name: 'api_client')]
final class ClientController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ClientRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('', methods: ['POST'])]
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
    public function show(Client $client): JsonResponse
    {
        return $this->json($client);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Client $client, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $client->setName($data['name']);
        $client->setEmail($data['email']);
        $client->setPhone($data['phone']);

        $em->flush();

        return $this->json($client);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Client $client, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($client);
        $em->flush();

        return $this->json(null, 204);
    }
}
