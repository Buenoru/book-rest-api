<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator;

class UserFixtures extends Fixture
{
    private EntityManagerInterface $em;
    private Generator $faker;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->faker = FakerFactory::create();
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $users = [
            $this->userFactory(),
            $this->userFactory(),
        ];

        foreach ($users as $i => $user) {
            $user->setAmount(($i + 1) * 100);
            $manager->persist($user);
        }

        // @todo: Это не тут должно быть. Чисто для экономии времени.
        $table = $manager->getClassMetadata(User::class)->table['name'];
        $this->em->getConnection()->executeStatement(sprintf('TRUNCATE "%s" RESTART IDENTITY CASCADE', $table));

        $manager->flush();
    }

    /**
     * @todo: Нарушение SRP
     */
    private function userFactory(): User
    {
        $user = new User();
        $user->setName($this->faker->name);

        return $user;
    }
}
