<?php

namespace App\DataFixtures;

use App\Entity\Deployment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DeploymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $deployments = [
            // Deployments for Release 1.0 (released)
            [
                'releaseName' => 'Release 1.0',
                'jobName' => 'deploy-to-qa',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'qa', 'branch' => 'release/1.0'],
                'buildNumber' => 1001,
                'createdAt' => '2023-12-20 14:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            [
                'releaseName' => 'Release 1.0',
                'jobName' => 'deploy-to-stage',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'stage', 'branch' => 'release/1.0'],
                'buildNumber' => 1002,
                'createdAt' => '2023-12-25 12:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            [
                'releaseName' => 'Release 1.0',
                'jobName' => 'deploy-to-prod',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'prod', 'branch' => 'release/1.0'],
                'buildNumber' => 1003,
                'createdAt' => '2024-01-01 10:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            
            // Deployments for Release 1.1 (in_stg)
            [
                'releaseName' => 'Release 1.1',
                'jobName' => 'deploy-to-qa',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'qa', 'branch' => 'release/1.1'],
                'buildNumber' => 1004,
                'createdAt' => '2023-12-18 15:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            [
                'releaseName' => 'Release 1.1',
                'jobName' => 'deploy-to-stage',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'stage', 'branch' => 'release/1.1'],
                'buildNumber' => 1005,
                'createdAt' => '2023-12-22 16:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            
            // Deployments for Release 1.2 (in_qa)
            [
                'releaseName' => 'Release 1.2',
                'jobName' => 'deploy-to-qa',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'qa', 'branch' => 'release/1.2'],
                'buildNumber' => 1006,
                'createdAt' => '2023-12-15 14:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            
            // Deployments for Release 2.1 (cancelled)
            [
                'releaseName' => 'Release 2.1',
                'jobName' => 'deploy-to-qa',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'qa', 'branch' => 'release/2.1'],
                'buildNumber' => 1007,
                'createdAt' => '2023-11-15 10:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            [
                'releaseName' => 'Release 2.1',
                'jobName' => 'deploy-to-stage',
                'status' => 'FAILED',
                'parameters' => ['environment' => 'stage', 'branch' => 'release/2.1'],
                'buildNumber' => 1008,
                'createdAt' => '2023-11-18 11:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            
            // Deployments for Release 2.2 (released)
            [
                'releaseName' => 'Release 2.2',
                'jobName' => 'deploy-to-qa',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'qa', 'branch' => 'release/2.2'],
                'buildNumber' => 1009,
                'createdAt' => '2024-01-20 15:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            [
                'releaseName' => 'Release 2.2',
                'jobName' => 'deploy-to-stage',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'stage', 'branch' => 'release/2.2'],
                'buildNumber' => 1010,
                'createdAt' => '2024-01-25 16:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            [
                'releaseName' => 'Release 2.2',
                'jobName' => 'deploy-to-prod',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'prod', 'branch' => 'release/2.2'],
                'buildNumber' => 1011,
                'createdAt' => '2024-02-01 09:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            
            // Deployments for Release 3.1 (in_qa)
            [
                'releaseName' => 'Release 3.1',
                'jobName' => 'deploy-to-qa',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'qa', 'branch' => 'release/3.1'],
                'buildNumber' => 1012,
                'createdAt' => '2024-03-10 14:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            
            // Deployments for Release 3.2 (in_stg)
            [
                'releaseName' => 'Release 3.2',
                'jobName' => 'deploy-to-qa',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'qa', 'branch' => 'release/3.2'],
                'buildNumber' => 1013,
                'createdAt' => '2024-03-01 14:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
            [
                'releaseName' => 'Release 3.2',
                'jobName' => 'deploy-to-stage',
                'status' => 'SUCCESS',
                'parameters' => ['environment' => 'stage', 'branch' => 'release/3.2'],
                'buildNumber' => 1014,
                'createdAt' => '2024-03-05 10:00:00',
                'triggeredBy' => 'jenkins_user'
            ],
        ];

        foreach ($deployments as $data) {
            $deployment = new Deployment();
            $deployment->setJobName($data['jobName']);
            $deployment->setReleaseName($data['releaseName']);
            $deployment->setParameters($data['parameters']);
            $deployment->setStatus($data['status']);
            $deployment->setBuildNumber($data['buildNumber']);
            $deployment->setCreatedAt(new \DateTime($data['createdAt']));
            $deployment->setTriggeredBy($data['triggeredBy']);
            
            $manager->persist($deployment);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ReleaseFixtures::class,
        ];
    }
}