<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace protocteur\PhantomJs\Tests\Unit\Procedure;

use protocteur\PhantomJs\Procedure\ProcedureFactoryInterface;
use protocteur\PhantomJs\Procedure\ProcedureLoaderFactory;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@protocteur.me>
 */
class ProcedureLoaderFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test directory
     *
     * @var string
     * @access protected
     */
    protected $directory;

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test invalid argument exceptions is thrown if directory
     * is not readable when creating procedure loader.
     *
     * @access public
     * @return void
     */
    public function testInvalidArgumentExceptionIsThrownIfDirectoryIsNotReadableWhenCreatingProcedureLoader()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $procedureFactory = $this->getProcedureFactory();

        $procedureLoaderFactory = $this->getProcedureLoaderFactory($procedureFactory);
        $procedureLoaderFactory->createProcedureLoader('invalid/directory');
    }

    /**
     * Test procedure loader can be created.
     *
     * @access public
     * @return void
     */
    public function testProcedureLoaderCanBeCreated()
    {
        $procedureFactory = $this->getProcedureFactory();

        $procedureLoaderFactory = $this->getProcedureLoaderFactory($procedureFactory);
        $procedureLoader = $procedureLoaderFactory->createProcedureLoader($this->directory);

        $this->assertInstanceOf('\protocteur\PhantomJs\Procedure\ProcedureLoaderInterface', $procedureLoader);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure loader factory instance.
     *
     * @access public
     * @param  \protocteur\PhantomJs\Procedure\ProcedureFactoryInterface $procedureFactory
     * @return \protocteur\PhantomJs\Procedure\ProcedureLoaderFactory
     */
    protected function getProcedureLoaderFactory(ProcedureFactoryInterface $procedureFactory)
    {
        $procedureLoaderFactory = new ProcedureLoaderFactory($procedureFactory);

        return $procedureLoaderFactory;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure factory.
     *
     * @access protected
     * @return \protocteur\PhantomJs\Procedure\ProcedureFactoryInterface
     */
    protected function getProcedureFactory()
    {
        $procedureFactory = $this->getMock('\protocteur\PhantomJs\Procedure\ProcedureFactoryInterface');

        return $procedureFactory;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++ UTILITIES ++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Set up test environment.
     *
     * @access public
     * @return void
     */
    public function setUp()
    {
        $this->directory = sys_get_temp_dir();

        if (!is_readable($this->directory)) {
            throw new \RuntimeException(sprintf('Test directory must be readable: %s', $this->directory));
        }
    }
}
