<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $passwordEncoder;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, SluggerInterface $slugger)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->generateAdmin(1);
        $this->manager->flush();
        $this->generateUser(10);
        $this->manager->flush();
    }

    private function generateAdmin(int $number): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Bezhanov\Faker\Provider\Avatar($faker));

        for ($i = 1; $i <= $number; $i++) {

            $admin = new User();
            $admin->setPseudo('admin')
                ->setEmail('admin@miam-miam.fr')
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'))
                ->setAvatar('https://randomuser.me/api/portraits/men/35.jpg')
                ->setIsRgpd(true)
                ->setIsVerified(true)
                ->setDownloadToken('admin')
                ->setSlug($this->slugger->slug($admin->getPseudo())->lower())
                ->setBiography('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>');

            $this->manager->persist($admin);
        }
    }

    private function generateUser(int $number): void
    {
        $faker = Factory::create('fr_FR');
        $users = [];
        $genres = ['male', 'female'];

        for ($j = 1; $j <= $number; $j++) {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $avatar = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $avatar .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            [ //destructuring
                'dateObject' => $dateObject,
                'dateString' => $dateString
            ] = $this->generateRandomDate('01/01/2021', '25/08/2021');

            $user->setPseudo("user{$j}")
                ->setEmail("user{$j}@miam-miam.fr")
                ->setPassword($this->passwordEncoder->hashPassword($user, 'toto'))
                ->setAvatar($avatar)
                ->setIsRgpd(true)
                ->setIsVerified(true)
                ->setDownloadToken('user')
                ->setSlug($this->slugger->slug($user->getPseudo("user-{$j}-{$dateString}"))->lower())
                ->setCreatedAt($dateObject)
                ->setBiography('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>');

            $this->manager->persist($user);
            $users[] = $user;
        }
    }

    private function generateRandomDate(string $start, string $end): array
    {
        $startDateTimestamp = (\DateTime::createFromFormat('d/m/Y', $start))->getTimestamp();
        $endDateTimestamp = (\DateTime::createFromFormat('d/m/Y', $end))->getTimestamp();
        $randomTimestamp = mt_rand($startDateTimestamp, $endDateTimestamp);
        $dateTimeImmutable = (new \DateTimeImmutable())->setTimestamp($randomTimestamp);

        return [
            'dateObject' => $dateTimeImmutable,
            'dateString' => $dateTimeImmutable->format('d-m-Y')
        ];

    }
}
