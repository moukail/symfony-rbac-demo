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

        $parentPermission1 = (new Permission())
            ->setIdentifier('MANAGE_ROLES')
            ->setLabel('Manage roles')
        ;
        $manager->persist($parentPermission1);

        $childPermission1 = (new Permission())
            ->setIdentifier('LIST_ROLES')
            ->setLabel('List roles')
            ->setParent($parentPermission1)
        ;
        $manager->persist($childPermission1);

        $childPermission2 = (new Permission())
            ->setIdentifier('VIEW_ROLE')
            ->setLabel('View roles')
            ->setParent($parentPermission1)
        ;
        $manager->persist($childPermission2);

        $childPermission3 = (new Permission())
            ->setIdentifier('ADD_ROLE')
            ->setLabel('Add role')
            ->setParent($parentPermission1)
        ;
        $manager->persist($childPermission3);

        $childPermission4 = (new Permission())
            ->setIdentifier('EDIT_ROLE')
            ->setLabel('Edit role')
            ->setParent($parentPermission1)
        ;
        $manager->persist($childPermission4);

        $childPermission5 = (new Permission())
            ->setIdentifier('DELETE_ROLE')
            ->setLabel('Delete role')
            ->setParent($parentPermission1)
        ;
        $manager->persist($childPermission5);

        $parentPermission2 = (new Permission())
            ->setIdentifier('MANAGE_USERS')
            ->setLabel('Manage users')
        ;
        $manager->persist($parentPermission2);

        $childPermission6 = (new Permission())
            ->setIdentifier('LIST_USERS')
            ->setLabel('List users')
            ->setParent($parentPermission2)
        ;
        $manager->persist($childPermission6);

        $childPermission7 = (new Permission())
            ->setIdentifier('VIEW_USER')
            ->setLabel('View user')
            ->setParent($parentPermission2)
        ;
        $manager->persist($childPermission7);

        $childPermission8 = (new Permission())
            ->setIdentifier('ADD_USER')
            ->setLabel('Add user')
            ->setParent($parentPermission2)
        ;
        $manager->persist($childPermission8);

        $childPermission9 = (new Permission())
            ->setIdentifier('EDIT_USER')
            ->setLabel('Edit user')
            ->setParent($parentPermission2)
        ;
        $manager->persist($childPermission9);

        $childPermission10 = (new Permission())
            ->setIdentifier('DELETE_USER')
            ->setLabel('Delete user')
            ->setParent($parentPermission2)
        ;
        $manager->persist($childPermission10);

        $manager->flush();
    }
}