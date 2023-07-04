<?php

namespace App\Command\Divalto;

use App\Entity\Job;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

#[AsCommand(
    name: 'Divalto:Import-articleC',
    description: 'Synchronisation des articles Divalto',
)]
class ImportArticleCCommand extends Command
{

    protected static $defaultDescription = 'Synchronisation des articles Divalto.';

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

        parent::__construct();
    }
    

    // ...
    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to import divalto articles...')
            ->addArgument('dossier', InputArgument::REQUIRED, 'Dossier')
            ->addArgument('jalon', InputArgument::REQUIRED, 'Modifié ajouté depuis (jours).')
        ;

    ;
    }
 

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['Lancement de l import des articles...']); 
        $httpClient = HttpClient::create();
        $job = new Job();
        $job->setName('Import Articles');
        $job->setDescription('Import des articles Divalto du dossier '. $input->getArgument('dossier'). ' modifié et ajouté depuis ' . $input->getArgument('jalon'));
        $job->setExecutedAt(new DateTimeImmutable());
        $response = $httpClient->request('POST',$_ENV['HOST_API'].'/api/articlesC/import/' . $input->getArgument('dossier') . '/' . $input->getArgument('jalon'));
        if (200 == $response->getStatusCode()) {
            $output->writeln($response->getContent());
            $job->setStatus(1);
            $job->setResult($response->getContent());
            $job->setProgress(100);
            $this->em->persist($job);
            $this->em->flush();
            return Command::SUCCESS;
        } else {
            //$output->writeln($response->getInfo('error'));
            $output->writeln("erreur");
            $job->setStatus(0);
            $job->setResult($response->getInfo('error'));
            $job->setProgress(100);
            $this->em->persist($job);
            $this->em->flush();
            return Command::FAILURE;
        }
    }


}
