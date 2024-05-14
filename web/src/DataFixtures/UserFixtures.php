<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $password = $this->passwordHasher->hashPassword(new User(), 'pass_1234');

        $user = (new User())
            ->setFirstName('Admin')
            ->setLastName('Admin')
            ->setEmail('ismail@moukafih.nl')
            ->setInactive(false)
            ->setPassword($password)
            ->setRole($this->getReference('admin-role', Role::class))
        ;

        $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
        ];
    }
}
