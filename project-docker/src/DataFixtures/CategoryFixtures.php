<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $data = ['plats', 'grillades', 'salades', 'entrées', 'dessets'];

        foreach ($data as $key => $c) {
            $category = new Category();
            $category->setName($c)
                ->setSlug($this->slugger->slug(strtolower($c)));

            $manager->persist($category);
            $this->addReference("category-{$key}", $category);
        }

        $manager->flush();
    }
}
