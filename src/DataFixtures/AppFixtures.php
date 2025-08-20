<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $userPasswordHasherInterface;


    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $existing= $manager->getRepository(User::class)->findOneBy(['email'=>'superadmin@industriecommerce.gouv.sn']);
        if(!$existing){
            $user=new User();
            $user->setEmail("superadmin@industriecommerce.gouv.sn");
            $user->setRoles(['ROLE_SUPERAMDIN', 'ROLE_ADMIN']);
            $user->setPseudo("SuperAdmin");
            $user->setPassword(
                $this->userPasswordHasherInterface->hashPassword($user,'SuperAdmin.2025#')
            );

            $manager->persist($user);
            $manager->flush();
        }

        $manager->flush();
    }
}
