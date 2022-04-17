<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace neokyuubi\PhantomJs\Procedure;

use Symfony\Component\Config\FileLocator;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@neokyuubi.me>
 */
class ProcedureLoaderFactory implements ProcedureLoaderFactoryInterface
{
    /**
     * Procedure factory.
     *
     * @var \neokyuubi\PhantomJs\Procedure\ProcedureFactoryInterface
     * @access protected
     */
    protected $procedureFactory;

    /**
     * Internal constructor.
     *
     * @access public
     * @param \neokyuubi\PhantomJs\Procedure\ProcedureFactoryInterface $procedureFactory
     */
    public function __construct(ProcedureFactoryInterface $procedureFactory)
    {
        $this->procedureFactory = $procedureFactory;
    }

    /**
     * Create procedure loader instance.
     *
     * @access public
     * @param  string                                      $directory
     * @return \neokyuubi\PhantomJs\Procedure\ProcedureLoader
     */
    public function createProcedureLoader($directory)
    {
        $procedureFactory = $this->procedureFactory;
        $fileLocator      = $this->createFileLocator($directory);

        $procedureLoader = new ProcedureLoader(
            $procedureFactory,
            $fileLocator
        );

        return $procedureLoader;
    }

    /**
     * Create file locator instance.
     *
     * @access protected
     * @param  string                                $directory
     * @return \Symfony\Component\Config\FileLocator
     * @throws \InvalidArgumentException
     */
    protected function createFileLocator($directory)
    {
        if (!is_dir($directory) || !is_readable($directory)) {
            throw new \InvalidArgumentException(sprintf('Could not create procedure loader as directory does not exist or is not readable: "%s"', $directory));
        }

        return new FileLocator($directory);
    }
}
