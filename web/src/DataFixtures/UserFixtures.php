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
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $password = $this->passwordHasher->hashPassword(new User(), 'pass_1234');
        $role = $manager->getRepository(Role::class)->findOneBy(['name' => Role::ROLE_ADMIN]);

        $user = (new User())
            ->setFirstName('Admin')
            ->setLastName('Admin')
            ->setEmail('ismail@moukafih.nl')
            ->setInactive(false)
            ->setPassword($password)
            ->setRole($role)
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
