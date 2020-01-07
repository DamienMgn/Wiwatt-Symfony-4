<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Nelmio\Alice\Loader\NativeLoader;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $loader = new NativeLoader($faker);
        
        // Importe le fichier de fixtures et récupère les entités générés
        $entities = $loader->loadFile(__DIR__.'/fixtures.yaml')->getObjects();
        
        // Persiste chacun des objets à enregistrer en BDD
        foreach ($entities as $entity) {
            $manager->persist($entity);
        };
        
        // Flush pour exécuter les requêtes SQL
        $manager->flush();
    }
}