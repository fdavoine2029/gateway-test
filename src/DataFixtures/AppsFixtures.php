<?php

namespace App\DataFixtures;

use App\Entity\Apps;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class AppsFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
        private SluggerInterface $slugger)
    {       
    }
    public function load(ObjectManager $manager): void
    {
        $app1 = new Apps();
        $app1->setCode('recpt');
        $app1->setName('Réceptions');
        $app1->setDescription('Gestion des réceptions');
        $app1->setSlug('reception');
        $app1->setAppOrder(1);
        $app1->setCreatedAt(new DateTimeImmutable());

        $app2 = new Apps();
        $app2->setCode('ctrqa');
        $app2->setName('Contrôle qualité');
        $app2->setDescription('Gestion des contrôles qualités');
        $app2->setSlug('controlequalite');
        $app2->setAppOrder(2);
        $app2->setCreatedAt(new DateTimeImmutable());

        $app3 = new Apps();
        $app3->setCode('sklbl');
        $app3->setName('Scalabel');
        $app3->setDescription('Gestion flux Scalabel');
        $app3->setSlug('scalabel');
        $app3->setAppOrder(3);
        $app3->setCreatedAt(new DateTimeImmutable());

    
        $manager->persist($app1);
        $manager->persist($app2);
        $manager->persist($app3);

        $manager->flush();
    }
}
