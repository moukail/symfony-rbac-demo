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
        $manageUsers = $manager->getRepository(Permission::class)->findOneBy(['identifier' => 'MANAGE_USERS']);
        $viewDashboard = $manager->getRepository(Permission::class)->findOneBy(['identifier' => 'VIEW_DASHBOARD']);

        $adminRole = (new Role())
            ->setName(Role::ROLE_ADMIN)
            ->addPermission(
                $this->getReference('manage-roles-permission', Permission::class)
            )
            ->addPermission($manageUsers)
            ->addPermission($viewDashboard)
        ;
        $manager->persist($adminRole);

        $role = new Role();
        $role->setName(Role::ROLE_MANAGER);
        $manager->persist($role);

        $role = new Role();
        $role->setName(Role::ROLE_USER);
        $manager->persist($role);

        $manager->flush();

        $this->addReference('admin-role', $adminRole);
    }

    /**
     * @return list<class-string<Fixture>>
     */
    public function getDependencies(): array
    {
        return [
            PermissionFixtures::class,
        ];
    }
}
