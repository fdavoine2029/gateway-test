<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class UsersFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger)
    {       
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setEmail('admin@neyret.com');
        $admin->setLastname('Davoine');
        $admin->setFirstname('Frédéric');
        $admin->setAddress('12 rue du port');
        $admin->setZipcode('75001');
        $admin->setCity('Paris');
        $admin->setPassword($this->passwordEncoder->hashPassword($admin, 'admin'));
        $admin->setIsVerified(1);
        $admin->setResetToken(0);
        $admin->setRoles(['ROLE_ADMIN']);

        $admin2 = new Users();
        $admin2->setEmail('fdavoine@neyret.com');
        $admin2->setLastname('Davoine');
        $admin2->setFirstname('Frédéric');
        $admin2->setAddress('12 rue du port');
        $admin2->setZipcode('75001');
        $admin2->setCity('Paris');
        $admin2->setPassword($this->passwordEncoder->hashPassword($admin2, 'azergues'));
        $admin2->setIsVerified(1);
        $admin2->setResetToken(0);
        $admin2->setRoles(['ROLE_ADMIN']);
    
        $manager->persist($admin);
        $manager->persist($admin2);
        $manager->flush();
    }
}
