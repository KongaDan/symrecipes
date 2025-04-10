<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR'); // Initialise Faker
        $ingredients = [];
        $users = [];
        $admin = new User();
        $admin->setEmail('kongadan68@gmail.com');
        $admin->setFullname('Konga Dan');
        $admin->setPseudo('kongadan super dev');
        $admin->setRoles(['ROLE_USER']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'password123'));
        $users[]=$admin;
        $manager->persist($admin);
        
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail())
                ->setFullName($faker->name())
                ->setPseudo($faker->userName())
                ->setRoles([$faker->randomElement(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_MODERATOR'])])
                ->setPassword('password_test_dev');
            $users[]=$user;

            $manager->persist($user);
        }

        for ($i = 0; $i < 25; $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($faker->name());
            $ingredient->setPrice($faker->randomNumber(2))
            ->setUser($users[mt_rand(0,count($users)-1)]);
            
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
            ->setUser($users[mt_rand(0,count($users)-1)])
            ->setIsFavorite(mt_rand(0,1) == 1? true: false)
            ->setIsPublic(mt_rand(0,1) == 1? true: false)
            ;

            for ($k=0; $k <mt_rand(5, 15) ; $k++) { 
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients)-1)]);
            }
            
            
            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
