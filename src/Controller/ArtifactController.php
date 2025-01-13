<?php 
namespace App\Controller;

use App\Entity\Artifact;
use App\Entity\Release;
use App\Repository\ArtifactRepository;
use App\Repository\ReleaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtifactController extends AbstractController
{
    private ArtifactRepository $artifactRepository;
    private ReleaseRepository $releaseRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ArtifactRepository $artifactRepository, ReleaseRepository $releaseRepository, EntityManagerInterface $entityManager)
    {
        $this->artifactRepository = $artifactRepository;
        $this->releaseRepository = $releaseRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('api/releases/{releaseName}/artifacts', name: 'get_release_artifacts', methods: ['GET'])]
    public function getReleaseArtifacts(string $releaseName): JsonResponse
    {
        $artifacts = $this->artifactRepository->findByReleaseName($releaseName);
        $data = array_map(function ($artifact) {
            return [
                'id' => $artifact->getId(),
                'name' => $artifact->getName(),
                'status' => $artifact->getStatus(),
                'version' => $artifact->getVersion(),
                'buildNum' => $artifact->getBuildNum(),
                'referenceNumber' => $artifact->getReferenceNumber(),
                'buildDateTime' => $artifact->getBuildDateTime()?->format('Y-m-d H:i:s'),
            ];
        }, $artifacts);
    
        return $this->json($data);
    }

    #[Route('api/releases/{releaseName}/artifacts/{artifactName}', name: 'create_or_update_artifact', methods: ['PUT'])]
    public function createOrUpdateArtifact(string $releaseName, string $artifactName, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        // Fetch the release entity using the release name
        $release = $this->releaseRepository->findOneBy(['name' => $releaseName]);

        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        // Try to find the artifact
        $artifact = $this->artifactRepository->findOneBy([
            'name' => $artifactName
        ]);

        if (!$artifact) {
            // Create a new artifact if it doesn't exist
            $artifact = new Artifact();
            $artifact->setName($artifactName);
        }

        // Update artifact properties
        $artifact->setVersion($data['version'] ?? $artifact->getVersion());
        $artifact->setReferenceNumber($data['referenceNumber'] ?? $artifact->getReferenceNumber());
        $artifact->setBuildNum($data['buildNum'] ?? $artifact->getBuildNum());
        $artifact->setBuildDateTime(new \DateTime($data['buildDateTime'] ?? $artifact->getBuildDateTime()?->format('Y-m-d H:i:s')));
        $artifact->setStatus($data['status'] ?? $artifact->getStatus());

        // Add the release to the artifact (relationship)
        if (!$artifact->getReleases()->contains($release)) {
            $artifact->addRelease($release);
        }

        $this->entityManager->persist($artifact);
        $this->entityManager->flush();

        return $this->json($artifact, Response::HTTP_OK);
    }

    #[Route('api/releases/{releaseName}/artifacts/{artifactName}', name: 'patch_artifact', methods: ['PATCH'])]
    public function patchArtifact(string $releaseName, string $artifactName, Request $request): JsonResponse
    {
        $artifact = $this->artifactRepository->findOneBy([
            'name' => $artifactName
        ]);

        if (!$artifact) {
            return $this->json(['message' => 'Artifact not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Update only the provided fields
        if (isset($data['name'])) {
            $artifact->setName($data['name']);
        }
        if (isset($data['version'])) {
            $artifact->setVersion($data['version']);
        }
        if (isset($data['referenceNumber'])) {
            $artifact->setReferenceNumber($data['sourceRef']);
        }
        if (isset($data['buildNum'])) {
            $artifact->setBuildNum($data['buildNum']);
        }
        if (isset($data['buildDateTime'])) {
            $artifact->setBuildDateTime(new \DateTime($data['buildDateTime']));
        }
        if (isset($data['status'])) {
            $artifact->setStatus($data['status']);
        }

        $this->entityManager->flush();

        return $this->json($artifact, Response::HTTP_OK);
    }

    #[Route('api/releases/{releaseName}/artifacts/{artifactName}', name: 'delete_artifact_from_release', methods: ['DELETE'])]
    public function deleteArtifactFromRelease(string $releaseName, string $artifactName): JsonResponse
    {
        $release = $this->releaseRepository->findOneBy(['name' => $releaseName]);

        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $artifact = $this->artifactRepository->findOneBy(['name' => $artifactName]);

        if (!$artifact) {
            return $this->json(['message' => 'Artifact not found'], Response::HTTP_NOT_FOUND);
        }

        // Disassociate the artifact from the release
        if ($artifact->getReleases()->contains($release)) {
            $artifact->removeRelease($release);
        }

        // Set the status to "removed"
        $artifact->setStatus('removed');

        $this->entityManager->flush();

        return $this->json(['message' => 'Artifact removed from release'], Response::HTTP_OK);
    }

    #[Route('api/artifacts', name: 'get_all_artifacts', methods: ['GET'])]
    public function getAllArtifacts(): JsonResponse
    {
        $artifacts = $this->artifactRepository->findAll();
        $data = array_map(function ($artifact) {
            $releaseNames = array_map(function ($release) {
                return $release->getName();
            }, $artifact->getReleases()->toArray());
    
            return [
                'id' => $artifact->getId(),
                'name' => $artifact->getName(),
                'status' => $artifact->getStatus(),
                'version' => $artifact->getVersion(),
                'buildNum' => $artifact->getBuildNum(),
                'referenceNumber' => $artifact->getReferenceNumber(),
                'buildDateTime' => $artifact->getBuildDateTime()?->format('Y-m-d H:i:s'),
                'releaseNames' => $releaseNames,
            ];
        }, $artifacts);
    
        return $this->json($data);
    }    
}
