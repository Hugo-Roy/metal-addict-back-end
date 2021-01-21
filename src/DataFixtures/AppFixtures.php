<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Band;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Review;
use App\Entity\Country;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\ShareOMetalProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->passwordEncoder = $userPasswordEncoder;
    }

    private function truncate(Connection $connection)
    {
        // reset ids
        $users = $connection->executeQuery('SET foreign_key_checks = 0');
        $users = $connection->executeQuery('TRUNCATE TABLE band');
        $users = $connection->executeQuery('TRUNCATE TABLE country');
        $users = $connection->executeQuery('TRUNCATE TABLE event');
        $users = $connection->executeQuery('TRUNCATE TABLE picture');
        $users = $connection->executeQuery('TRUNCATE TABLE review');
        $users = $connection->executeQuery('TRUNCATE TABLE user');
    }
    
    public function load(ObjectManager $manager)
    {
        $this->truncate($manager->getConnection());

        $faker = Faker\Factory::create('fr_FR');

        $faker->seed('Lyra PHP');

        $faker->addProvider(new ShareOMetalProvider());
        
        // $provider = new ShareOMetalProvider();

        //create bands
        $bands = $faker->getBands();
        $bandsCollection = [];
        foreach ($bands as $bandName) {
            $band = new Band();
            $band->setName($bandName);
            $bandsCollection[] = $band;
            $manager->persist($band);
        };

        //create countries
        $countries = $faker->getCountries();
        $countriesCollection = [];
        foreach ($countries as $countryName => $countryCode) {
            $country = new Country();
            $country->setName($countryName);
            $country->setCountryCode($countryCode);
            $countriesCollection[] = $country;
            $manager->persist($country);
        };

        //create 50 events
        $eventsCollection = [];
        for ($i=0; $i < 50; $i++) { 
            $event = new Event();
            $event->setVenue($faker->getVenue());
            $event->setCity($faker->getCity());
            $event->setCountry($countriesCollection[mt_rand(0, count($countriesCollection) - 1)]);
            $event->setBand($bandsCollection[mt_rand(0, count($bandsCollection) - 1)]);
            $event->setDate($faker->dateTimeBetween("-20 years"));
            $eventsCollection[] = $event;
            $manager->persist($event);
        }

        //TODO create pictures

        //create 5 users
        $usersCollection = [];

        $firstUser = new User();
        $firstUser->setEmail('lemmy@lemmy.com');
        $firstUser->setPassword($this->passwordEncoder->encodePassword($firstUser, 'lemmy'));
        $firstUser->setNickname('Lemmy Killmister');
        $firstUser->setRoles(['ROLE_USER']);
        for ($i=0; $i < (mt_rand(5, 20)); $i++) { 
            $firstUser->addEvent($eventsCollection[mt_rand(0, 50)]);
        };
        $usersCollection[] = $firstUser;
        $manager->persist($firstUser);

        $secondUser = new User();
        $secondUser->setEmail('josh@josh.com');
        $secondUser->setPassword($this->passwordEncoder->encodePassword($secondUser, 'josh'));
        $secondUser->setNickname('Josh Homme');
        $secondUser->setRoles(['ROLE_USER']);
        for ($i=0; $i < (mt_rand(5, 20)); $i++) { 
            $secondUser->addEvent($eventsCollection[mt_rand(0, 50)]);
        };
        $usersCollection[] = $secondUser;
        $manager->persist($secondUser);

        $thirdUser = new User();
        $thirdUser->setEmail('phil@phil.com');
        $thirdUser->setPassword($this->passwordEncoder->encodePassword($thirdUser, 'phil'));
        $thirdUser->setNickname('Phil Anselmo');
        $thirdUser->setRoles(['ROLE_USER']);
        for ($i=0; $i < (mt_rand(5, 20)); $i++) { 
            $thirdUser->addEvent($eventsCollection[mt_rand(0, 50)]);
        };
        $usersCollection[] = $thirdUser;
        $manager->persist($thirdUser);

        $fourthUser = new User();
        $fourthUser->setEmail('jerry@jerry.com');
        $fourthUser->setPassword($this->passwordEncoder->encodePassword($fourthUser, 'jerry'));
        $fourthUser->setNickname('Jerry Cantrell');
        $fourthUser->setRoles(['ROLE_USER']);
        for ($i=0; $i < (mt_rand(5, 20)); $i++) { 
            $fourthUser->addEvent($eventsCollection[mt_rand(0, 50)]);
        };
        $usersCollection[] = $fourthUser;
        $manager->persist($fourthUser);

        $fifthUser = new User();
        $fifthUser->setEmail('dimebag@dimebag.com');
        $fifthUser->setPassword($this->passwordEncoder->encodePassword($fifthUser, 'dimebag'));
        $fifthUser->setNickname('Dimebag Darrell');
        for ($i=0; $i < (mt_rand(5, 20)); $i++) { 
            $fifthUser->addEvent($eventsCollection[mt_rand(0, 50)]);
        };
        $fifthUser->setRoles(['ROLE_USER']);
        $usersCollection[] = $fifthUser;
        $manager->persist($fifthUser);


        //create 200 reviews
        for ($i=0; $i < 200; $i++) { 
            $review = new Review();
            $review->setTitle($faker->sentence(10));
            $review->setContent($faker->realText());
            $review->setUser($usersCollection[mt_rand(0, count($usersCollection) - 1)]);
            $review->setEvent($eventsCollection[mt_rand(0, count($bandsCollection) - 1)]);
            $manager->persist($review);
        }

        $manager->flush();
    }
}
