<?php 
namespace App\Controller;

use App\Entity\Release;
use App\Repository\ReleaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReleaseController extends AbstractController
{
    private ReleaseRepository $releaseRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ReleaseRepository $releaseRepository, EntityManagerInterface $entityManager)
    {
        $this->releaseRepository = $releaseRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('api/releases', name: 'get_all_releases', methods: ['GET'])]
    public function getAllReleases(): JsonResponse
    {
        $releases = $this->releaseRepository->findAll();
        $data = array_map(function ($release) {
            return [
                'id' => $release->getId(),
                'name' => $release->getName(),
                'status' => $release->getStatus(),
                'productionDate' => $release->getProductionDate()?->format('Y-m-d H:i:s'),
                'qaDate' => $release->getQaDate()?->format('Y-m-d H:i:s'),
                'stageDate' => $release->getStageDate()?->format('Y-m-d H:i:s'),
                'mainReleaseTicket' => $release->getMainReleaseTicket()
            ];
        }, $releases);
    
        return $this->json($data);
    }

    #[Route('api/release/{name}', name: 'get_release_by_name', methods: ['GET'])]
    public function getReleaseByName(string $name): JsonResponse
    {
        $release = $this->releaseRepository->findOneBy(['name' => $name]);
        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }
        $data = [
                'id' => $release->getId(),
                'name' => $release->getName(),
                'status' => $release->getStatus(),
                'productionDate' => $release->getProductionDate()?->format('Y-m-d H:i:s'),
                'qaDate' => $release->getQaDate()?->format('Y-m-d H:i:s'),
                'stageDate' => $release->getStageDate()?->format('Y-m-d H:i:s'),
                'mainReleaseTicket' => $release->getMainReleaseTicket()
            ];

        return $this->json($data);
    }

    #[Route('api/release', name: 'create_release', methods: ['POST'])]
    public function createRelease(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $release = new Release();
        $release->setName($data['name']);
        $release->setStatus($data['status']);
        $release->setProductionDate(new \DateTime($data['productionDate']));
        $release->setQaDate(new \DateTime($data['qaDate']));
        $release->setStageDate(new \DateTime($data['stageDate']));
        $release->setMainReleaseTicket($data['mainReleaseTicket']);

        $this->entityManager->persist($release);
        $this->entityManager->flush();

        return $this->json($release, Response::HTTP_CREATED);
    }

    #[Route('api/release/{name}', name: 'update_release', methods: ['PUT'])]
    public function updateRelease(string $name, Request $request): JsonResponse
    {
        $release = $this->releaseRepository->findOneBy(['name' => $name]);

        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        $release->setName($data['name']);
        $release->setStatus($data['status']);
        $release->setProductionDate(new \DateTime($data['productionDate']));
        $release->setQaDate(new \DateTime($data['qaDate']));
        $release->setStageDate(new \DateTime($data['stageDate']));
        $release->setMainReleaseTicket($data['mainReleaseTicket']);

        $this->entityManager->flush();

        return $this->json($release);
    }

    #[Route('api/release/{name}', name: 'delete_release', methods: ['DELETE'])]
    public function deleteRelease(string $name): JsonResponse
    {
        $release = $this->releaseRepository->findOneBy(['name' => $name]);

        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($release);
        $this->entityManager->flush();

        return $this->json(['message' => 'Release deleted successfully'], Response::HTTP_OK);
    }
}