<?php

namespace App\DataFixtures;

use App\Entity\Artifact;
use App\Entity\Release;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtifactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Fetch references to releases created in ReleaseFixtures
        $release1 = $manager->getRepository(Release::class)->findOneBy(['name' => 'Release 1.0']);
        $release2 = $manager->getRepository(Release::class)->findOneBy(['name' => 'Release 1.1']);
        $release3 = $manager->getRepository(Release::class)->findOneBy(['name' => 'Release 2.0']);
        $release4 = $manager->getRepository(Release::class)->findOneBy(['name' => 'Release 2.2']);
        $release5 = $manager->getRepository(Release::class)->findOneBy(['name' => 'Release 3.1']);

        $artifacts = [
            [
                'name' => 'Artifact A1',
                'version' => '1.0.0',
                'referenceNumber' => 'REF-001',
                'buildNum' => '1001',
                'buildDateTime' => '2023-12-01 10:00:00',
                'status' => 'accepted',
                'releases' => [$release1],
            ],
            [
                'name' => 'Artifact A2',
                'version' => '1.0.1',
                'referenceNumber' => 'REF-002',
                'buildNum' => '1002',
                'buildDateTime' => '2023-12-05 11:00:00',
                'status' => 'accepted',
                'releases' => [$release1, $release2],
            ],
            [
                'name' => 'Artifact A3',
                'version' => '1.1.0',
                'referenceNumber' => 'REF-003',
                'buildNum' => '1003',
                'buildDateTime' => '2023-12-10 12:00:00',
                'status' => 'pending',
                'releases' => [$release2],
            ],
            [
                'name' => 'Artifact A4',
                'version' => '2.0.0',
                'referenceNumber' => 'REF-004',
                'buildNum' => '2001',
                'buildDateTime' => '2024-01-01 09:00:00',
                'status' => 'pending',
                'releases' => [$release3],
            ],
            [
                'name' => 'Artifact A5',
                'version' => '2.0.1',
                'referenceNumber' => 'REF-005',
                'buildNum' => '2002',
                'buildDateTime' => '2024-01-15 10:00:00',
                'status' => 'accepted',
                'releases' => [$release4],
            ],
            [
                'name' => 'Artifact A6',
                'version' => '2.1.0',
                'referenceNumber' => 'REF-006',
                'buildNum' => '3001',
                'buildDateTime' => '2024-02-01 11:00:00',
                'status' => 'pending',
                'releases' => [$release5],
            ],
            [
                'name' => 'Artifact A7',
                'version' => '3.0.0',
                'referenceNumber' => 'REF-007',
                'buildNum' => '3002',
                'buildDateTime' => '2024-02-15 12:00:00',
                'status' => 'declined',
                'releases' => [],
            ],
            [
                'name' => 'Artifact A8',
                'version' => '3.0.1',
                'referenceNumber' => 'REF-008',
                'buildNum' => '3003',
                'buildDateTime' => '2024-03-01 13:00:00',
                'status' => 'pending',
                'releases' => [$release5],
            ],
            [
                'name' => 'Artifact A9',
                'version' => '3.1.0',
                'referenceNumber' => 'REF-009',
                'buildNum' => '3004',
                'buildDateTime' => '2024-03-10 14:00:00',
                'status' => 'accepted',
                'releases' => [$release5],
            ],
            [
                'name' => 'Artifact A10',
                'version' => '3.2.0',
                'referenceNumber' => 'REF-010',
                'buildNum' => '3005',
                'buildDateTime' => '2024-03-20 15:00:00',
                'status' => 'accepted',
                'releases' => [],
            ],
        ];

        foreach ($artifacts as $data) {
            $artifact = new Artifact();
            $artifact->setName($data['name']);
            $artifact->setVersion($data['version']);
            $artifact->setReferenceNumber($data['referenceNumber']);
            $artifact->setBuildNum($data['buildNum']);
            $artifact->setBuildDateTime(new \DateTime($data['buildDateTime']));
            $artifact->setStatus($data['status']);

            foreach ($data['releases'] as $release) {
                if ($release) {
                    $artifact->addRelease($release);
                    $release->addArtifact($artifact);
                }
            }

            $manager->persist($artifact);
        }

        $manager->flush();
    }
}
