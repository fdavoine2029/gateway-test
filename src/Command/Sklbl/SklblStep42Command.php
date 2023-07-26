<?php

namespace App\Command\Sklbl;

use App\Entity\Job;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;

#[AsCommand(
    name: 'Sklbl:Import-step42',
    description: 'Scalabel Génération csv et demande de transfert',
)]
class SklblStep42Command extends Command
{

    protected static $defaultDescription = 'Scalabel Génération csv et demande de transfert.';

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
            ->setHelp('This command allows you to generate csv for F1 and ask transfert...')
        ;

    ;
    }
 

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['Lancement de la génération des fichiers csv et demande de transfert...']); 
        $httpClient = HttpClient::create();
        $job = new Job();
        $job->setName('Scalabel_Step_42.');
        $job->setDescription('Scalabel_Step_42 Génération des enregistrements des fichiers csv et demande de transfert...');
        $job->setExecutedAt(new DateTimeImmutable());
        $response = $httpClient->request('POST',$_ENV['HOST_API'].'/sklbl/api/step_42');
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
            if($response->getInfo('error')){
                $job->setResult($response->getInfo('error'));
            }else{
                $job->setResult($response->getStatusCode());
            }
            
            $job->setProgress(100);
            $this->em->persist($job);
            $this->em->flush();
            return Command::FAILURE;
        }
    }


}
