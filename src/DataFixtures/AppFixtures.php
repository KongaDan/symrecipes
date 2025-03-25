<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Initialise Faker
        $ingredients = [];
        for ($i = 0; $i < 25; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->name());
            $ingredient->setPrice($faker->randomNumber(2));
            
            $ingredients[]= $ingredient;
            $manager->persist($ingredient);
        }


        for ($i = 0; $i < 25; $i++) {
            $recipe = new Recipe();
            $recipe->setName($faker->name())
            ->setTime(mt_rand(0,1) == 1? mt_rand(1,1440) : null)
            ->setNbPeople(mt_rand(0,1) == 1? mt_rand(1,49) : null)
            ->setDifficulty(mt_rand(0,1) == 1? mt_rand(1,5) : null)
            ->setDescription($faker->text(300))
            ->setPrice(mt_rand(0,1) == 1? mt_rand(1,1000) : null)
            ->setIsFavorite(mt_rand(0,1) == 1? true: false);

            for ($k=0; $k <mt_rand(5, 15) ; $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients)-1)]);
            }
            
            
            $manager->persist($recipe);
        }
        $manager->flush();
    }
}
