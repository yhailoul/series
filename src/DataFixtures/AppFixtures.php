<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $serie = new Serie();
            $serie
                ->setBackdrop('backdrop.png')
                ->setDateCreated(new \DateTime())
                ->setFirstAirDate($faker->dateTimeBetween('-5 years', 'now'))
                ->setName($faker->text(20))
                ->setGenres($faker->randomElement(['sf','comedy','drama','romance','western','thriller','horror']));
                $serie->setLastAirDate($faker->dateTimeBetween($serie->getFirstAirDate(), 'now'))
                ->setPopularity($faker->numberBetween(0, 9999))
                ->setPoster('poster.png')
                ->setStatus($faker->randomElement(['cancelled','returning','ended']))
                ->setTmdbId(12345)
                ->setVote($faker->numberBetween(0, 10));
            $manager->persist($serie);
        }
        $manager->flush();
    }
}
