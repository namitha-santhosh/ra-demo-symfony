<?php

namespace App\DataFixtures;

use App\Entity\Release;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReleaseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $releases = [
            [
                'name' => 'Release 1.0',
                'status' => 'released',
                'productionDate' => '2024-01-01 10:00:00',
                'qaDate' => '2023-12-20 14:00:00',
                'stageDate' => '2023-12-25 12:00:00',
                'mainReleaseTicket' => 'REL-1001',
            ],
            [
                'name' => 'Release 1.1',
                'status' => 'in_stg',
                'productionDate' => null,
                'qaDate' => '2023-12-18 15:00:00',
                'stageDate' => '2023-12-22 16:00:00',
                'mainReleaseTicket' => 'REL-1002',
            ],
            [
                'name' => 'Release 1.2',
                'status' => 'in_qa',
                'productionDate' => null,
                'qaDate' => '2023-12-15 14:00:00',
                'stageDate' => null,
                'mainReleaseTicket' => 'REL-1003',
            ],
            [
                'name' => 'Release 1.3',
                'status' => 'open',
                'productionDate' => null,
                'qaDate' => null,
                'stageDate' => null,
                'mainReleaseTicket' => 'REL-1004',
            ],
            [
                'name' => 'Release 2.0',
                'status' => 'open',
                'productionDate' => null,
                'qaDate' => null,
                'stageDate' => null,
                'mainReleaseTicket' => 'REL-2001',
            ],
            [
                'name' => 'Release 2.1',
                'status' => 'cancelled',
                'productionDate' => null,
                'qaDate' => '2023-11-15 10:00:00',
                'stageDate' => '2023-11-18 11:00:00',
                'mainReleaseTicket' => 'REL-2002',
            ],
            [
                'name' => 'Release 2.2',
                'status' => 'released',
                'productionDate' => '2024-02-01 09:00:00',
                'qaDate' => '2024-01-20 15:00:00',
                'stageDate' => '2024-01-25 16:00:00',
                'mainReleaseTicket' => 'REL-2003',
            ],
            [
                'name' => 'Release 3.0',
                'status' => 'open',
                'productionDate' => null,
                'qaDate' => null,
                'stageDate' => null,
                'mainReleaseTicket' => 'REL-3001',
            ],
            [
                'name' => 'Release 3.1',
                'status' => 'in_qa',
                'productionDate' => null,
                'qaDate' => '2024-03-10 14:00:00',
                'stageDate' => null,
                'mainReleaseTicket' => 'REL-3002',
            ],
            [
                'name' => 'Release 3.2',
                'status' => 'in_stg',
                'productionDate' => null,
                'qaDate' => '2024-03-01 14:00:00',
                'stageDate' => '2024-03-05 10:00:00',
                'mainReleaseTicket' => 'REL-3003',
            ],
        ];

        foreach ($releases as $data) {
            $release = new Release();
            $release->setName($data['name']);
            $release->setStatus($data['status']);
            $release->setProductionDate($data['productionDate'] ? new \DateTime($data['productionDate']) : null);
            $release->setQaDate($data['qaDate'] ? new \DateTime($data['qaDate']) : null);
            $release->setStageDate($data['stageDate'] ? new \DateTime($data['stageDate']) : null);
            $release->setMainReleaseTicket($data['mainReleaseTicket']);
            $manager->persist($release);
        }

        $manager->flush();
    }
}
