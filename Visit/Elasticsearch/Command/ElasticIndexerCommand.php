<?php


namespace Visit\Elasticsearch\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Visit\Elasticsearch\ElasticIndexer;
#[AsCommand(
    name: 'elastic:indexer',
    description: 'Mise à jour des données [all, posts, categories, offres]',
)]
class ElasticIndexerCommand extends Command
{
    private SymfonyStyle $io;

    protected function configure()
    {
        $this
            ->addArgument('action', InputArgument::REQUIRED, 'all, posts, categories, bottin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = $input->getArgument('action');
        $this->io = new SymfonyStyle($input, $output);
        $elastic = new ElasticIndexer($this->io);
        $result = $elastic->treatment();

        if (isset($result['error'])) {
            $this->io->error($result['error']);

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
