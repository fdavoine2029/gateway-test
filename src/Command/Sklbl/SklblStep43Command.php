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
    name: 'Sklbl:Import-step43',
    description: 'Appel API Azure pour transfert fichiers F1 en attente',
)]
class SklblStep43Command extends Command
{

    protected static $defaultDescription = 'Appel API Azure pour transfert fichiers F1 en attente.';

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
            ->setHelp('This command allows you to send F1...')
        ;

    ;
    }
 

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(['Appel API Azure pour transfert fichiers F1 en attente...']); 
        $httpClient = HttpClient::create();
        $job = new Job();
        $job->setName('Scalabel_Step_43.');
        $job->setDescription('Scalabel_Step_43 Appel API Azure pour transfert fichiers F1 en attente...');
        $job->setExecutedAt(new DateTimeImmutable());
        $response = $httpClient->request('POST',$_ENV['HOST_API'].'/sklbl/api/step_43');
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
