<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegionFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $data = [
            [
                'name' => 'Sud est',
                'description' => 'Cuisine du sud est de la France',
                'picture' => 'https://via.placeholder.com/350x350'
            ],
            [
                'name' => 'Normandie',
                'description' => 'Cuisine de Normandie',
                'picture' => 'https://via.placeholder.com/350x350'
            ],
            [
                'name' => 'Savoie',
                'description' => 'cuisine de Savoie',
                'picture' => 'https://via.placeholder.com/350x350'
            ],
            [
                'name' => 'Sud Ouest',
                'description' => 'cuisine du sud Ouest',
                'picture' => 'https://via.placeholder.com/350x350'
            ]
        ];

        foreach ($data as $key => $r) {
            $region = new Region();
            $region->setName($r['name'])
                ->setDescription($r['description'])
                ->setSlug($this->slugger->slug(strtolower($r['name'])))
                ->setPicture($r['picture']);

            $manager->persist($region);

            $this->addReference("region-{$key}", $region);
        }

        $manager->flush();
    }
}
