<?php

namespace App\Command;

use App\Entity\Quote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RandomQuoteCommand extends Command
{
    private EntityManagerInterface $em;
    // le nom de la commande (la partie après "bin/console")
    protected static $defaultName = 'app:random-quote';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get a random quote.')

            ->setHelp('This command allows you to fetch a random quote, you can also filter it by category...')
            ->addOption(
                'category',
                null,
                InputOption::VALUE_OPTIONAL,
                'Filter by category',
                null
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $em = $this->em;

        $repo = $em->getRepository(Quote::class);

        $res = $repo->random();

        if (null == $res) {
            $io->error('No category found');
        } elseif (null != $input->getOption('category') && $input->getOption('category') != $res->getCategory()) {
            $io->error('Unknown "'.$input->getOption('category').'" category');
        } else {
            $io->success('Random quote :');
            $io->text($res->getContent());
            $io->newLine();
            $io->text($res->getMeta());
            $io->newLine();
            if (null != $input->getOption('category')) {
                $io->text($input->getOption('category'));
            }
        }

        return 0;
    }
}
