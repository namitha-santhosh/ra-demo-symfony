<?php

namespace App\Controller;

use App\Entity\Deployment;
use App\Service\JenkinsDeploymentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class DeploymentController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/deploy', name: 'api_deploy', methods: ['POST'])]
    public function deploy(Request $request, JenkinsDeploymentService $jenkinsService): JsonResponse
    {
        $rawContent = $request->getContent();
        
        $parameters = json_decode($rawContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], 400);
        }
        
        if (!isset($parameters['appName'], $parameters['appVersion'], $parameters['deployEnv'])) {
            return new JsonResponse([
                'error' => 'Missing required parameters. Please provide appName, appVersion, and deployEnv'
            ], 400);
        }

        try {
            $result = $jenkinsService->triggerDeployment(
                'deploymentjob',
                $parameters['releaseName'],
                $parameters
            );
            
            return new JsonResponse($result);
        } catch (\Exception $e) {
            return new JsonResponse([
                'error' => 'Deployment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/api/deployments', name: 'api_get_deployments', methods: ['GET'])]
    public function getDeployments(Request $request): JsonResponse
    {
        $limit = $request->query->get('limit', 10);
        $deployments = $this->entityManager->getRepository(Deployment::class)
            ->findLatestDeployments($limit);
            
        return $this->json([
            'deployments' => $this->formatDeployments($deployments)
        ]);
    }

    #[Route('/api/releases/{releaseName}/deployments', name: 'api_get_release_deployments', methods: ['GET'])]
    public function getReleaseDeployments(string $releaseName): JsonResponse
    {
        $deployments = $this->entityManager->getRepository(Deployment::class)
            ->findReleaseDeployments($releaseName);

        return $this->json([
            'releaseName' => $releaseName,
            'deployments' => $this->formatDeployments($deployments)
        ]);
    }

    #[Route('/api/jenkins/webhook', name: 'api_jenkins_webhook', methods: ['POST'])]
    public function jenkinsWebhook(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $buildNumber = $data['build']['number'] ?? null;
        $buildResult = $data['build']['status'] ?? $data['build']['phase'] ?? null;
        $jobName = $data['name'] ?? null;

        if ($buildNumber && $jobName) {
            $deployment = $this->entityManager->getRepository(Deployment::class)
                ->findOneBy([
                    'jobName' => $jobName,
                    'buildNumber' => $buildNumber
                ]);

            if ($deployment) {
                $status = match ($buildResult) {
                    'SUCCESS' => 'SUCCESS',
                    'FAILURE' => 'FAILED',
                    'ABORTED' => 'ABORTED',
                    'UNSTABLE' => 'UNSTABLE',
                    'STARTED', 'IN_PROGRESS' => 'IN_PROGRESS',
                    default => $deployment->getStatus()
                };

                $deployment->setStatus($status);
                $this->entityManager->flush();
            }
        }

        return new JsonResponse(['status' => 'ok']);
    }

    private function formatDeployments(array $deployments): array
    {
        return array_map(function($deployment) {
            return [
                'id' => $deployment->getId(),
                'jobName' => $deployment->getJobName(),
                'releaseName' => $deployment->getReleaseName(),
                'parameters' => $deployment->getParameters(),
                'status' => $deployment->getStatus(),
                'buildNumber' => $deployment->getBuildNumber(),
                'triggeredBy' => $deployment->getTriggeredBy(),
                'createdAt' => $deployment->getCreatedAt()->format('c'),
                'updatedAt' => $deployment->getUpdatedAt()?->format('c'),
            ];
        }, $deployments);
    }
}