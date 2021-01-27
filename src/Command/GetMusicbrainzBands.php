<?php

namespace App\Command;

use App\Entity\Band;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GetMusicbrainzBands extends Command
{
    protected static $defaultName = 'app:get:bands';

    private $client;

    private $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;

        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Gets bands.')
            ->setHelp('This command allows you to get all metal bands from Musicbrainz into your database. ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isBands = false;
        $offset = 0;

        while ($isBands === false) {
            $isBands = $this->fetchFromMusicbrainz($offset, $output);
            $offset += 100;
            sleep(1);
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
                    'Accept-Language' => 'fr',
                ],
            ]
        );
       
        $bands =  $response->toArray();

        foreach ($bands['artists'] as $band) {
            $output->writeln($band['id']);
            $output->writeln($band['name']);
            $bandEntity = new Band();
            $bandEntity->setMusicbrainzId($band['id']);
            $bandEntity->setName($band['name']);
            $this->entityManager->persist($bandEntity);
        }

        $this->entityManager->flush();

        return empty($bands['artists']);
    }
}
