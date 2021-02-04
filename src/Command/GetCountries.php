<?php

namespace App\Command;

use App\Entity\Country;
use App\Service\SetlistApi;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetCountries extends Command {
    protected static $defaultName = 'app:get:bands';

    private $setlistApi;

    private $entityManager;

    private $connection;

    public function __construct(SetlistApi $setlistApi, EntityManagerInterface $entityManager, Connection $connection)
    {
        $this->setlistApi = $setlistApi;

        $this->entityManager = $entityManager;

        $this->connection = $connection;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Gets countries.')
            ->setHelp('This command allows you to get all countries avialable in Setlist.fm into your database. ')
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        $this->connection->executeQuery('TRUNCATE TABLE country');
        $output->writeln('Country table truncated.');

        $countries = json_decode($this->setlistApi->getCountries());

        foreach ($countries as $country) {
            $countryEntity = new Country();
            $countryEntity->setCountryCode($country['code']);
            $countryEntity->setName($country['name']);
            $this->entityManager->persist($countryEntity);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }

}