<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{
    
        // $product = new Product();
        // $manager->persist($product);
        // $faker = \Faker\Factory::create('fr_FR');

        // for($i = 1; $i <= mt_rand(8, 10); $i++)
        // {
        //     $produit = new Produit;

        //     $produit->setNom($faker->sentence(3))
        //             ->setImage($faker->imageUrl)
        //             ->setPrix($faker->randomFloat(2, 10, 100))
        //             ->setDescription($faker->"hello");
        //     $manager->persist($produit);
        // }

        public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for($i = 1; $i <= 20; $i++)
        {
            $Category = new Categorie;
            $Category->settitre($faker->sentence(3));
            $manager->persist($Category);
        }
        for($u = 1; $u <= 20; $u++)
        {
            $User = new User;
            $User->setemail($faker->sentence(3))
            ->setpassword($faker->sentence(3))
            ->setusername($faker->sentence(3));
            $manager->persist($User);
        }
        for($j = 1; $j <= 20; $j++)
        {
            $Product=new Produit;
            $Product->setCategorieId($Category)
            ->setUserId($User)
            ->setnom($faker->sentence(3))
            ->setdescription($faker->sentence(3))
            ->setprix($faker->randomFloat(2,10,100))
            ->setImage($faker->imageUrl);
            $manager->persist($Product);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}