<?php

namespace App\Command;

use App\Entity\Beer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'beer:import',
    description: 'this command allow import beers from an api request',
)]
class BeerImportCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly HttpClientInterface $httpClient
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('numPages', null, InputOption::VALUE_REQUIRED, 'Number of page for process')
        ;
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $numPages = filter_var($input->getOption('numPages') ?? 1, FILTER_VALIDATE_INT);


       for($numPage=1;$numPage<=$numPages;$numPage++){

            $response = $this->httpClient->request(
                'GET',
                sprintf( 'https://api.punkapi.com/v2/beers?page=%s&per_page=80',$numPage)
            );

            $statusCode = $response->getStatusCode();

            $output->writeln(sprintf('<info>import beers has been succesfully code %s!</info>',$statusCode));

            try {

                $beers = $response->toArray();

                $progressBar = new ProgressBar($output, sizeof($beers));

                $progressBar->start();
                foreach ($beers as $beerAr){

                    $beer = new Beer();
                    $beer->setName($beerAr['name']);
                    $beer->setTips($beerAr['brewers_tips']);
                    $beer->setDescription($beerAr['description']);
                    $beer->setFirstBrewed($beerAr['first_brewed']);
                    $beer->setImageUrl($beerAr['image_url']);
                    $beer->setTagLine($beerAr['tagline']);

                    $this->entityManager->persist($beer);
                    $this->entityManager->flush();

                    $progressBar->advance();

                }

                $progressBar->finish();


            } catch (ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            } catch (TransportExceptionInterface $e) {
                $output->writeln(sprintf('<warning>import beers has been succesfully code %s!</warning>',$e->getMessage()));
            }

        }

        return Command::SUCCESS;
    }
}
