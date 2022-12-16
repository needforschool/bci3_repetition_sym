<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setEmail('quidelantoine@gmail.com')
            ->setRoles(['ROLE_SUPER_ADMIN','ROLE_ADMIN','ROLE_USER']);
        $admin->setPassword($this->hasher->hashPassword($admin,'michel'));
        $manager->persist($admin);

        $abonne = (new User())
            ->setEmail('quidelantoine@yahoo.com')
            ->setRoles(['ROLE_USER']);
        $abonne->setPassword($this->hasher->hashPassword($abonne,'michel'));
        $manager->persist($abonne);


        $manager->flush();
    }
}
