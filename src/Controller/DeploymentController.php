<?php

namespace App\Controller;

use App\Entity\Deployment;
use App\Repository\DeploymentRepository;
use App\Repository\ReleaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeploymentController extends AbstractController
{
    private DeploymentRepository $deploymentRepository;
    private ReleaseRepository $releaseRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(DeploymentRepository $deploymentRepository, ReleaseRepository $releaseRepository, EntityManagerInterface $entityManager)
    {
        $this->deploymentRepository = $deploymentRepository;
        $this->releaseRepository = $releaseRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('api/releases/{releaseName}/deployments', name: 'create_deployment', methods: ['POST'])]
    public function createDeployment(string $releaseName, Request $request): JsonResponse
    {
        $release = $this->releaseRepository->findOneBy(['name' => $releaseName]);

        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $deployment = new Deployment();
        $deployment->setSlug($data['slug']);
        $deployment->setRelease($release);

        $this->entityManager->persist($deployment);
        $this->entityManager->flush();

        return $this->json(['message' => 'Deployment created successfully'], Response::HTTP_CREATED);
    }

    #[Route('api/releases/{releaseName}/deployments', name: 'get_release_deployments', methods: ['GET'])]
    public function getReleaseDeployments(string $releaseName): JsonResponse
    {
        $release = $this->releaseRepository->findOneBy(['name' => $releaseName]);

        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $deployments = $this->deploymentRepository->findBy(['release' => $release]);

        $data = array_map(function (Deployment $deployment) {
            return [
                'id' => $deployment->getId(),
                'slug' => $deployment->getSlug(),
            ];
        }, $deployments);

        return $this->json($data);
    }

    #[Route('api/deployments/{slug}', name: 'delete_deployment', methods: ['DELETE'])]
    public function deleteDeployment(string $slug): JsonResponse
    {
        $deployment = $this->deploymentRepository->findOneBy(['slug' => $slug]);

        if (!$deployment) {
            return $this->json(['message' => 'Deployment not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($deployment);
        $this->entityManager->flush();

        return $this->json(['message' => 'Deployment deleted successfully'], Response::HTTP_OK);
    }
}
