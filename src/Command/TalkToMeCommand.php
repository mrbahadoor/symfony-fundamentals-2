<?php

namespace App\Command;

use App\Service\MixRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:talk-to-me',
    description: 'A software command that can do... only one thing',
)]
class TalkToMeCommand extends Command
{
    public function __construct(
        private MixRepository $mixRepository
    ) {
        parent::__construct();
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::OPTIONAL, 'Your name')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'Shall I yell?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // If no name is passed, the default value is used 
        $name = $input->getArgument('name') ?: 'Whoever you are';
        $shouldYell = $input->getOption('yell');

        $message = sprintf('Hey %s!', $name);

        if($shouldYell){
            $message = strtoupper($message);
        }

        $io->success($message);
        // $io->warning('This is a warning message');
        // $io->error('This is an error message');
        // $io->note('This is a note message');
        // $io->table(
        //     ['Header 1', 'Header 2'],
        //     [
        //         ['Cell 1-1', 'Cell 1-2'],
        //         ['Cell 2-1', 'Cell 2-2'],
        //     ]
        // );
        // $io->caution('This is a caution message');

        if($io->confirm('Do you want a mix recommendation?')){
            $mixes = $this->mixRepository->findAll();
            $mix = $mixes[array_rand($mixes)];
            
            $io->note('I recommend you to listen to: ' . $mix['title']);
        }


        return Command::SUCCESS;
        // return Command::FAILURE;
    }
}
