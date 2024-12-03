<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Favorite;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setUsername('musso')
            ->setEmail('musso@localhost.fr')
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);

        // Récupérer le mot de passe du formulaire ou autre source
        $plainPassword = 'musso_password';
        $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $manager->persist($user);
        $manager->persist($user);

        $categorys = [];
        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category->setName($faker->sentence(3));
            $manager->persist($category);
            $categorys[] = $category;
        }

        $manager->flush();

        $recipes = [];
        for ($i = 0; $i < 10; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->sentence(3))
                ->setDescription($faker->sentence(10))
                ->setAuthor($user)
                ->setCategory($categorys[mt_rand(0, 2)]);

            $manager->persist($recipe);
            $recipes[] = $recipe;
        }

        $manager->flush();


        for ($i = 0; $i < 3; $i++) {
            $fav = new Favorite();
            $fav->setUser($user)
                ->setRecipe($recipes[mt_rand(0, 9)]);

            $manager->persist($fav);
        }

        $manager->flush();
    }
}
