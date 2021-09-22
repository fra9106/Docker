<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordEncoder;
    private SluggerInterface $slugger;
    private ObjectManager $manager;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, SluggerInterface $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->generateAdmin(1);
        $this->manager->flush();
        $this->generateUser(10);
        $this->manager->flush();
    }

    private function generateAdmin(): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Avatar($faker));

        $admin = new User();
        $admin->setPseudo('admin')
            ->setEmail('admin@miam-miam.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'))
            ->setAvatar('https://randomuser.me/api/portraits/men/35.jpg')
            ->setIsRgpd(true)
            ->setIsVerified(true)
            ->setDownloadToken('admin')
            ->setSlug($this->slugger->slug(strtolower('admin')))
            ->setBiography('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>');

        $this->manager->persist($admin);
        $this->addReference("admin", $admin);
    }

    private function generateUser(int $number): void
    {
        $faker = Factory::create('fr_FR');
        $users = [];
        $genres = ['male', 'female'];

        for ($i = 1; $i <= $number; $i++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $avatar = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            [ //destructuring
                'dateObject' => $dateObject

            ] = $this->generateRandomDate('01/01/2021', '25/08/2021');

            $user->setPseudo("user-{$i}")
                ->setEmail("user{$i}@miam-miam.fr")
                ->setPassword($this->passwordEncoder->hashPassword($user, 'toto'))
                ->setAvatar($avatar)
                ->setIsRgpd(true)
                ->setIsVerified(true)
                ->setDownloadToken('user')
                ->setSlug($this->slugger->slug(strtolower("user-{$i}")))
                ->setCreatedAt($dateObject)
                ->setBiography('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>');

            $this->manager->persist($user);
            //$this->addReference("user-{$number}", $user);
            $users[] = $user;
        }
    }

    /**
     * Genetate random date
     *
     * @param string $start
     * @param string $end
     * @return array{dateObject: \DateTimeImmutable}
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
            'dateObject' => $dateTimeImmutable
        ];
    }
}
