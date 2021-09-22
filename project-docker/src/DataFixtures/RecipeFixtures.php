<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;
    private ObjectManager $manager;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->generateRecipeAdmin(20);
        $this->manager->flush();
        //$this->generateRecipeUser(10);
        //$this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RegionFixtures::class,
            UserFixtures::class

        ];
    }
    public function generateRecipeAdmin(int $number): void
    {
        $faker = Factory::create('fr_FR');
        $data = [];
        for ($i = 1; $i <= $number; $i++) {
            $recipe = new Recipe();

            [ //destructuring
                'dateObject' => $dateObject,
                'dateString' => $dateString
            ] = $this->generateRandomDate('01/01/2021', '25/08/2021');


            $recipe->setRegion($this->getReference("region-" . mt_rand(0, 3)))
                ->setName("recette-{$i}")
                ->setUser($this->getReference("admin"))
                ->setContent($faker->paragraph('10'))
                ->setPicture('default.jpg')
                ->setIsActive(true)
                ->setIsSubmited(true)
                ->setSlug($this->slugger->slug(strtolower("recette-{$i}-du-{$dateString}")))
                ->setCreatedAt($dateObject);

            $this->manager->persist($recipe);
            $data[] = $recipe;
        }
    }

    /*public function generateRecipeUser(int $number): void
    {
        $faker = Factory::create('fr_FR');
        $data = [];
        for ($j = 1; $j <= $number; $j++) {
            $recipe = new Recipe();

            [ //destructuring
                'dateObject' => $dateObject,
                'dateString' => $dateString
            ] = $this->generateRandomDate('01/01/2021', '25/08/2021');


            $recipe->setRegion($this->getReference("region-" . mt_rand(0, 3)))
                ->setName("recette-{$j}")
                ->setUser($this->getReference("user-" . mt_rand(1, 11)))
                ->setContent($faker->paragraph('10'))
                ->setPicture('default.jpg')
                ->setIsActive(true)
                ->setIsSubmited(true)
                ->setSlug($this->slugger->slug(strtolower("recette-{$j}-du-{$dateString}")))
                ->setCreatedAt($dateObject);

            $this->manager->persist($recipe);
            $data[] = $recipe;
        }
    }*/

    /**
     * Genetate random date
     *
     * @param string $start
     * @param string $end
     * @return array{dateObject: \DateTimeImmutable, dateString: string }
     */
    private function generateRandomDate(string $start, string $end): array
    {
        $startDate = \DateTime::createFromFormat('d/m/Y', $start);
        $endDate = \DateTime::createFromFormat('d/m/Y', $end);

        if (!$startDate || !$endDate) {
            throw new HttpException(400, 'mauvais format de date');
        }

        $randomTimestamp = mt_rand($startDate->getTimestamp(), $endDate->getTimestamp());
        $dateTimeImmutable = (new \DateTimeImmutable())->setTimestamp($randomTimestamp);

        return [
            'dateObject' => $dateTimeImmutable,
            'dateString' => $dateTimeImmutable->format('d-m-Y')
        ];
    }
}
