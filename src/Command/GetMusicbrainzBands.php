<?php

namespace App\Command;

use App\Entity\Band;
use Doctrine\DBAL\Connection;
use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetMusicbrainzBands extends Command
{
    protected static $defaultName = 'app:get:bands';

    private $client;

    private $entityManager;

    private $connection;

    private $bandRepository;

    public function __construct(HttpClientInterface $client,BandRepository $bandRepository, EntityManagerInterface $entityManager, Connection $connection)
    {
        $this->client = $client;

        $this->entityManager = $entityManager;

        $this->connection = $connection;

        $this->bandRepository = $bandRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Gets bands.')
            ->setHelp('This command allows you to get all metal bands from Musicbrainz into your database. ')
            ->addOption('update', null, InputOption::VALUE_NONE, 'To update the band database instead of truncate and replace it.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $optionValue = $input->getOption('update');
        if (false === $optionValue) {
            $this->connection->executeQuery('SET foreign_key_checks = 0');
            $this->connection->executeQuery('TRUNCATE TABLE band');
            $output->writeln('Band table truncated.');
        }

        $isBands = false;
        $offset = 1;

        while ($isBands === false) {
            $isBands = $this->fetchFromMusicbrainz($offset, $output);
            $offset += 100;
            usleep(500000);
        };

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }

    private function fetchFromMusicbrainz($offset, $output)
    {
        $response = $this->client->request(
            'GET',
            'https://musicbrainz.org/ws/2/artist?query=tag:metal&limit=100&offset='.$offset,
            [
                'headers' => [
                    'Accept' =>'application/json',
                ],
            ]
        );
       
        $bands =  $response->toArray();

        foreach ($bands['artists'] as $band) {
            if ($this->bandRepository->findOneBy(['musicbrainzId' => $band['id']]) === null) {
                $output->writeln($band['name']);
                $bandEntity = new Band();
                $bandEntity->setMusicbrainzId($band['id']);
                $bandEntity->setName($band['name']);
                $this->entityManager->persist($bandEntity);
            }
        }

        $this->entityManager->flush();
        $this->entityManager->clear();

        return empty($bands['artists']);
    }
}
