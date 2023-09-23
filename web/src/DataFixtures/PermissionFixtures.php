<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PermissionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $permission = (new Permission())
            ->setIdentifier('VIEW_DASHBOARD')
            ->setLabel('View dashboard')
        ;
        $manager->persist($permission);

        $permission = (new Permission())
            ->setIdentifier('MANAGE_ROLES')
            ->setLabel('Manage roles')
        ;
        $manager->persist($permission);

        $permission = (new Permission())
            ->setIdentifier('MANAGE_USERS')
            ->setLabel('Manage users')
        ;
        $manager->persist($permission);

        $permission = (new Permission())
            ->setIdentifier('LIST_USERS')
            ->setLabel('List users')
        ;
        $manager->persist($permission);

        $permission = (new Permission())
            ->setIdentifier('VIEW_USER')
            ->setLabel('View user')
        ;
        $manager->persist($permission);

        $permission = (new Permission())
            ->setIdentifier('ADD_USER')
            ->setLabel('Add user')
        ;
        $manager->persist($permission);

        $permission = (new Permission())
            ->setIdentifier('EDIT_USER')
            ->setLabel('Edit user')
        ;
        $manager->persist($permission);

        $manager->flush();
    }
}