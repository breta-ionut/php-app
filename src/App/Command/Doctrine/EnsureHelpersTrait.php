<?php

namespace App\Command\Doctrine;

use App\Console\Application;
use App\Exception\LogicException;
use App\Exception\UnexpectedTypeException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;

/**
 * Trait which ensures Doctrine helpers for Doctrine commands by attaching to the helper set setter.
 */
trait EnsureHelpersTrait
{
    /**
     * The helper set setter.
     *
     * @param HelperSet $helperSet
     */
    public function setHelperSet(HelperSet $helperSet)
    {
        if (!$this instanceof Command) {
            throw new LogicException(sprintf('This trait must be used only be `%s` classes.', Command::class));
        }

        $application = $this->getApplication();
        if (!$application instanceof Application) {
            throw new UnexpectedTypeException($application, Application::class);
        }

        // Ensure the Doctrine helpers are present in the command's helper set.
        $entityManager = $application->getKernel()
            ->getContainer()
            ->get(EntityManagerInterface::class);
        foreach (ConsoleRunner::createHelperSet($entityManager) as $alias => $helper) {
            $helperSet->set($helper, $alias);
        }

        parent::setHelperSet($helperSet);
    }
}
