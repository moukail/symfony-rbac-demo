<?php

namespace App\DataFixtures;

use App\Entity\Permission;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $manageRoles = $manager->getRepository(Permission::class)->findOneBy(['identifier' => 'MANAGE_USERS']);
        $viewDashboard = $manager->getRepository(Permission::class)->findOneBy(['identifier' => 'VIEW_DASHBOARD']);

        $role = (new Role())
            ->setName(Role::ROLE_ADMIN)
            ->addPermission($manageRoles)
            ->addPermission($viewDashboard)
        ;
        $manager->persist($role);

        $role = new Role();
        $role->setName(Role::ROLE_MANAGER);
        $manager->persist($role);

        $role = new Role();
        $role->setName(Role::ROLE_USER);
        $manager->persist($role);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PermissionFixtures::class,
        ];
    }
}
