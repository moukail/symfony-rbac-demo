<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setName(Role::ROLE_ADMIN);
        $manager->persist($role);

        $role = new Role();
        $role->setName(Role::ROLE_MANAGER);
        $manager->persist($role);

        $role = new Role();
        $role->setName(Role::ROLE_USER);
        $manager->persist($role);

        $manager->flush();
    }
}
