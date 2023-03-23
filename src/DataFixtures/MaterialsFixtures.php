<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Materials;

class MaterialsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 3; $i++){
            $material = new Materials();
            $material->setNom("Material n°$i")
                     ->setPrix("$i €")
                     ->setNombre($i * 4)
                     ->setCreatedAt(new \Datetime());

                     $manager->persist($material);
        }

        $manager->flush();
    }
}
