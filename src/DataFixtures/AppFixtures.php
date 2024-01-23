<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('yen@gmail.com');
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword('$2a$04$McAxb5/iWfMpSiKxbdcCs.7.WNFuGk1xfHGuIbebfpo8NH4BknkQC');

        $manager->persist($user);

        $manager->flush();
    }
}
