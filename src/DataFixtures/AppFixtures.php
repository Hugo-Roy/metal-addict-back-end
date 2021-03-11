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
use App\Repository\BandRepository;
use App\Repository\CountryRepository;
use App\Service\SetlistApi;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    private $setlistApi;

    private $bandRepository;

    private $countryRepository;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, SetlistApi $setlistApi, BandRepository $bandRepository, CountryRepository $countryRepository)
    {
        $this->passwordEncoder = $userPasswordEncoder;
        $this->setlistApi = $setlistApi;
        $this->bandRepository = $bandRepository;
        $this->countryRepository = $countryRepository;
    }

    private function truncate(Connection $connection)
    {
        // reset ids
        $connection->executeQuery('SET foreign_key_checks = 0');
        $connection->executeQuery('TRUNCATE TABLE country');
        $connection->executeQuery('TRUNCATE TABLE event');
        $connection->executeQuery('TRUNCATE TABLE picture');
        $connection->executeQuery('TRUNCATE TABLE review');
        $connection->executeQuery('TRUNCATE TABLE user');
    }
    
    public function load(ObjectManager $manager)
    {
        $this->truncate($manager->getConnection());

        $faker = Faker\Factory::create('fr_FR');

        $faker->seed('Lyra PHP');

        $faker->addProvider(new ShareOMetalProvider());

        //create countries from setlist.fm
        $countriesContent = $this->setlistApi->getCountries();
        $countries = $countriesContent['country'];
        foreach ($countries as $country) {
            $countryEntity = new Country();
            $countryEntity->setCountryCode($country['code']);
            $countryEntity->setName($country['name']);
            $manager->persist($countryEntity);
        }

        $manager->flush();
        

        //create events
        $eventsCollection = [];
        $setlistIds = $faker->getSetlistIds();
        foreach ($setlistIds as $setlistId ) {
            $eventProperties = $this->setlistApi->fetchOneEvent($setlistId);
            $event = new Event();
            $event->setSetlistId($eventProperties['id']);
            $event->setVenue($eventProperties['venue']['name']);
            $event->setCity($eventProperties['venue']['city']['name']);
            $event->setDate(new \DateTime($eventProperties['eventDate']));
            $eventBand = $this->bandRepository->findOneBy(['musicbrainzId' => $eventProperties['artist']['mbid']]);
            $event->setBand($eventBand);
            $eventCountry = $this->countryRepository->findOneBy(['countryCode' => $eventProperties['venue']['city']['country']['code']]);
            $event->setCountry($eventCountry);
            $eventsCollection[] = $event;
            $manager->persist($event);
            sleep(1);
        }

        //create 5 users
        $usersCollection = [];

        $firstUser = new User();
        $firstUser->setEmail('lemmy@lemmy.com');
        $firstUser->setPassword($this->passwordEncoder->encodePassword($firstUser, 'lemmy'));
        $firstUser->setNickname('Lemmy Killmister');
        $firstUser->setRoles(['ROLE_USER']);
        $firstUser->setBiography($faker->realText());
        for ($i=0; $i < 6; $i++) { 
            $firstUser->addEvent($eventsCollection[mt_rand(0, count($setlistIds) - 1)]);
        };
        $usersCollection[] = $firstUser;
        $manager->persist($firstUser);

        $secondUser = new User();
        $secondUser->setEmail('josh@josh.com');
        $secondUser->setPassword($this->passwordEncoder->encodePassword($secondUser, 'josh'));
        $secondUser->setNickname('Josh Homme');
        $secondUser->setRoles(['ROLE_USER']);
        $secondUser->setBiography($faker->realText());
        for ($i=0; $i < 6; $i++) { 
            $secondUser->addEvent($eventsCollection[mt_rand(0, count($setlistIds) - 1)]);
        };
        $usersCollection[] = $secondUser;
        $manager->persist($secondUser);

        $thirdUser = new User();
        $thirdUser->setEmail('phil@phil.com');
        $thirdUser->setPassword($this->passwordEncoder->encodePassword($thirdUser, 'phil'));
        $thirdUser->setNickname('Phil Anselmo');
        $thirdUser->setRoles(['ROLE_USER']);
        $thirdUser->setBiography($faker->realText());
        for ($i=0; $i < 6; $i++) { 
            $thirdUser->addEvent($eventsCollection[mt_rand(0, count($setlistIds) - 1)]);
        };
        $usersCollection[] = $thirdUser;
        $manager->persist($thirdUser);

        $fourthUser = new User();
        $fourthUser->setEmail('jerry@jerry.com');
        $fourthUser->setPassword($this->passwordEncoder->encodePassword($fourthUser, 'jerry'));
        $fourthUser->setNickname('Jerry Cantrell');
        $fourthUser->setRoles(['ROLE_USER']);
        $fourthUser->setBiography($faker->realText());
        for ($i=0; $i < 6; $i++) { 
            $fourthUser->addEvent($eventsCollection[mt_rand(0, count($setlistIds) - 1)]);
        };
        $usersCollection[] = $fourthUser;
        $manager->persist($fourthUser);

        $fifthUser = new User();
        $fifthUser->setEmail('dimebag@dimebag.com');
        $fifthUser->setPassword($this->passwordEncoder->encodePassword($fifthUser, 'dimebag'));
        $fifthUser->setNickname('Dimebag Darrell');
        $fifthUser->setRoles(['ROLE_USER']);
        $fifthUser->setBiography($faker->realText());
        for ($i=0; $i < 6; $i++) { 
            $fifthUser->addEvent($eventsCollection[mt_rand(0, count($setlistIds) - 1)]);
        };
        $usersCollection[] = $fifthUser;
        $manager->persist($fifthUser);


        //create 6 reviews for each users
        foreach ($usersCollection as $user) {
            $userEvents = $user->getEvents();
            foreach ($userEvents as $userEvent ) {
                $review = new Review();
                $review->setTitle($faker->sentence(10));
                $review->setContent($faker->realText());
                $review->setUser($user);
                $review->setEvent($userEvent);
                $manager->persist($review);
            }
        }

        $manager->flush();
    }
}
