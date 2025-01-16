<?php 
namespace App\Controller;

use App\Entity\Artifact;
use App\Entity\ArtifactRelease;
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
        $release = $this->releaseRepository->findOneBy(['name' => $releaseName]);
        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $artifactReleases = $release->getArtifactReleases();

        $data = array_map(function (ArtifactRelease $artifactRelease) {
            return [
                'artifactId' => $artifactRelease->getArtifact()->getId(),
                'artifactName' => $artifactRelease->getArtifact()->getName(),
                'status' => $artifactRelease->getStatus(),
                'version' => $artifactRelease->getVersion(),
                'buildNum' => $artifactRelease->getBuildNum(),
                'buildDateTime' => $artifactRelease->getBuildDateTime()->format('Y-m-d H:i:s'),
                'referenceNumber' => $artifactRelease->getSourceRef(),
            ];
        }, $artifactReleases->toArray());

        return $this->json($data);
    }

    #[Route('api/releases/{releaseName}/artifacts/{artifactName}', name: 'create_or_update_artifact_release', methods: ['PUT'])]
    public function createOrUpdateArtifactRelease(string $releaseName, string $artifactName, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $release = $this->releaseRepository->findOneBy(['name' => $releaseName]);
        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $artifact = $this->artifactRepository->findOneBy(['name' => $artifactName]);
        if (!$artifact) {
            $artifact = new Artifact();
            $artifact->setName($artifactName);
            $this->entityManager->persist($artifact);
        }

        // Check if an ArtifactRelease already exists for this artifact and release
        $artifactRelease = $this->entityManager->getRepository(ArtifactRelease::class)
            ->findOneBy(['artifact' => $artifact, 'release' => $release]);

        if (!$artifactRelease) {
            $artifactRelease = new ArtifactRelease();
            $artifactRelease->setArtifact($artifact);
            $artifactRelease->setRelease($release);
        }

        // Update the fields
        $artifactRelease->setStatus($data['status']);
        $artifactRelease->setVersion($data['version']);
        $artifactRelease->setBuildNum($data['buildNum']);
        $artifactRelease->setBuildDateTime(new \DateTime($data['buildDateTime']));
        $artifactRelease->setSourceRef($data['sourceRef']);

        $this->entityManager->persist($artifactRelease);
        $this->entityManager->flush();

        return $this->json(['message' => 'Artifact created or updated successfully'], Response::HTTP_OK);
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

        // Find the ArtifactRelease for the specified artifact and release
        $artifactRelease = $this->entityManager->getRepository(ArtifactRelease::class)
            ->findOneBy(['artifact' => $artifact, 'release' => $release]);

        if (!$artifactRelease) {
            return $this->json(['message' => 'ArtifactRelease not found'], Response::HTTP_NOT_FOUND);
        }

        // Update the status to 'removed'
        $artifactRelease->setStatus('removed');
        $this->entityManager->flush();

        return $this->json(['message' => 'Artifact removed from release'], Response::HTTP_OK);
    }

    #[Route('api/artifacts', name: 'get_all_artifacts', methods: ['GET'])]
    public function getAllArtifacts(): JsonResponse
    {
        $artifacts = $this->artifactRepository->findAll();

        $data = array_map(function (Artifact $artifact) {
            return [
                'id' => $artifact->getId(),
                'name' => $artifact->getName(),
                'releases' => array_map(function (ArtifactRelease $artifactRelease) {
                    return [
                        'releaseName' => $artifactRelease->getRelease()->getName(),
                        'status' => $artifactRelease->getStatus(),
                        'version' => $artifactRelease->getVersion(),
                        'buildNum' => $artifactRelease->getBuildNum(),
                        'buildDateTime' => $artifactRelease->getBuildDateTime()->format('Y-m-d H:i:s'),
                        'referenceNumber' => $artifactRelease->getSourceRef(),
                    ];
                }, $artifact->getArtifactReleases()->toArray()),
            ];
        }, $artifacts);

        return $this->json($data);
    }

    #[Route('api/releases/{releaseName}/artifacts/{artifactName}', name: 'patch_artifact_release', methods: ['PATCH'])]
    public function patchArtifactRelease(string $releaseName, string $artifactName, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $release = $this->releaseRepository->findOneBy(['name' => $releaseName]);
        if (!$release) {
            return $this->json(['message' => 'Release not found'], Response::HTTP_NOT_FOUND);
        }

        $artifact = $this->artifactRepository->findOneBy(['name' => $artifactName]);
        if (!$artifact) {
            return $this->json(['message' => 'Artifact not found'], Response::HTTP_NOT_FOUND);
        }

        $artifactRelease = $this->entityManager->getRepository(ArtifactRelease::class)
            ->findOneBy(['artifact' => $artifact, 'release' => $release]);

        if (!$artifactRelease) {
            return $this->json(['message' => 'ArtifactRelease not found'], Response::HTTP_NOT_FOUND);
        }

        if (isset($data['status'])) {
            $artifactRelease->setStatus($data['status']);
        }
        if (isset($data['version'])) {
            $artifactRelease->setVersion($data['version']);
        }
        if (isset($data['buildNum'])) {
            $artifactRelease->setBuildNum($data['buildNum']);
        }
        if (isset($data['buildDateTime'])) {
            $artifactRelease->setBuildDateTime(new \DateTime($data['buildDateTime']));
        }
        if (isset($data['sourceRef'])) {
            $artifactRelease->setSourceRef($data['sourceRef']);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Artifact release updated successfully'], Response::HTTP_OK);
    }
}
