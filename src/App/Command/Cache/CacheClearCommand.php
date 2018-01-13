<?php

namespace App\Command\Cache;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * The cache clear command.
 */
class CacheClearCommand extends Command
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * The command constructor.
     *
     * @param string     $cacheDir
     * @param Filesystem $filesystem
     */
    public function __construct(string $cacheDir, Filesystem $filesystem)
    {
        parent::__construct();

        $this->cacheDir = $cacheDir;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('cache:clear')
            ->setDescription('Clears the application cache.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->filesystem->remove($this->cacheDir.'/*');

            $output->writeln('<info>Successfully cleared the cache!</info>');
        } catch (\Throwable $exception) {
            $output->writeln(sprintf(
                '<error>The cache clearing failed. Reported error: "%s".</error>',
                $exception->getMessage()
            ));
        }
    }
}
