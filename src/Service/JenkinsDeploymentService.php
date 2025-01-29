<?php

namespace App\Service;

use App\Entity\Deployment;
use App\Repository\DeploymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Security\Core\Security;

class JenkinsDeploymentService
{
    private $httpClient;
    private $jenkinsUrl;
    private $jenkinsUser;
    private $jenkinsToken;
    private $entityManager;
    private $security;

    public function __construct(
        HttpClientInterface $httpClient,
        EntityManagerInterface $entityManager,
        Security $security,
        string $jenkinsUrl,
        string $jenkinsUser,
        string $jenkinsToken
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->security = $security;
        
        $this->jenkinsUrl = $jenkinsUrl;
        $this->jenkinsUser = $jenkinsUser;
        $this->jenkinsToken = $jenkinsToken;
    }

    public function triggerDeployment(string $jobName, string $releaseName, array $parameters): array
    {
        $deployment = new Deployment();
        $deployment->setJobName($jobName);
        $deployment->setReleaseName($releaseName);
        $deployment->setParameters($parameters);
        $deployment->setTriggeredBy($this->security->getUser()?->getUserIdentifier());

        $this->entityManager->persist($deployment);
        $this->entityManager->flush();

        $jenkinsParameters = [
            'app_name' => $parameters['appName'] ?? 'p10demo-app',
            'app_version' => $parameters['appVersion'] ?? $releaseName,
            'deploy_env' => $parameters['deployEnv'] ?? 'p10-demo'
        ];
        
        $params = array_map(function($key, $value) {
            return $key . '=' . urlencode($value);
        }, array_keys($jenkinsParameters), $jenkinsParameters);
        
        $queryString = implode('&', $params);
        
        try {
            $buildEndpoint = sprintf(
                '%s/job/%s/buildWithParameters?%s',
                rtrim($this->jenkinsUrl, '/'),
                $jobName,
                $queryString
            );

            $response = $this->httpClient->request('POST', $buildEndpoint, [
                'auth_basic' => [$this->jenkinsUser, $this->jenkinsToken],
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $locationHeader = $response->getHeaders()['location'][0] ?? null;
            if ($locationHeader) {
                preg_match('/queue\/item\/(\d+)/', $locationHeader, $matches);
                $queueId = $matches[1] ?? null;
                
                $maxAttempts = 5;
                $buildNumber = null;
                
                for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
                    if ($attempt > 0) {
                        sleep(2);
                    }
                    
                    $queueEndpoint = sprintf(
                        '%s/queue/item/%s/api/json',
                        rtrim($this->jenkinsUrl, '/'),
                        $queueId
                    );
                    
                    $queueResponse = $this->httpClient->request('GET', $queueEndpoint, [
                        'auth_basic' => [$this->jenkinsUser, $this->jenkinsToken],
                        'headers' => [
                            'Accept' => 'application/json',
                        ],
                    ]);
                    
                    $queueData = json_decode($queueResponse->getContent(), true);
                    
                    if (isset($queueData['executable']['number'])) {
                        $buildNumber = $queueData['executable']['number'];
                        break;
                    }
    
                    if (isset($queueData['cancelled']) && $queueData['cancelled']) {
                        throw new \Exception('Build was cancelled in queue');
                    }
                    
                    if (isset($queueData['blocked']) && $queueData['blocked']) {
                        throw new \Exception('Build is blocked in queue');
                    }
                }
                
                if ($buildNumber) {
                    $deployment->setBuildNumber($buildNumber);
                    $deployment->setStatus('STARTED');
                } else {
                    $deployment->setStatus('QUEUED');
                    $deployment->setParameters(array_merge($deployment->getParameters(), ['queueId' => $queueId]));
                }
                
                $this->entityManager->flush();
            }
    
            return [
                'status' => $response->getStatusCode(),
                'deploymentId' => $deployment->getId(),
                'buildNumber' => $buildNumber ?? null,
                'queueId' => $queueId ?? null,
                'releaseName' => $releaseName,
                'parameters' => $jenkinsParameters
            ];
    
        } catch (\Exception $e) {
            $deployment->setStatus('FAILED');
            $this->entityManager->flush();
            
            throw $e;
        }
    }
}