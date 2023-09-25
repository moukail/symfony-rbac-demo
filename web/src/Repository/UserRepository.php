<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
    public function hasUserPermission(User $user, string $permission): bool
    {
        /*
            SELECT *
            FROM medewerkers m
            JOIN roles r on m.role_id = r.id
            JOIN roles_permissions rp on r.id = rp.role_id
            JOIN permissions parent on rp.permission_id = parent.id
            LEFT JOIN permissions child ON parent.id = child.parent
            WHERE m.email = 'ismail@jcid.nl' AND (parent.identifier = 'LIST_USERS' OR child.identifier = 'LIST_USERS')
            ;
         */

        $query = $this->createQueryBuilder('u')
            ->join('u.role', 'r')
            ->join('r.permissions', 'parent')
            ->leftJoin('parent.children', 'child')
            ->where('u.id = :userId')
            ->andWhere('parent.identifier = :permission OR child.identifier = :permission')
            ->setParameters([
                'userId' => $user->getId(),
                'permission' => $permission
            ])
            ->getQuery()
        ;

        $result = $query->getOneOrNullResult();

        return $result !== null;
    }


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
