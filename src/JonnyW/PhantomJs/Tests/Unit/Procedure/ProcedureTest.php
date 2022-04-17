<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace neokyuubi\PhantomJs\Tests\Unit\Procedure;

use Twig_Environment;
use Twig_Loader_String;
use neokyuubi\PhantomJs\Engine;
use neokyuubi\PhantomJs\Cache\FileCache;
use neokyuubi\PhantomJs\Cache\CacheInterface;
use neokyuubi\PhantomJs\Parser\JsonParser;
use neokyuubi\PhantomJs\Parser\ParserInterface;
use neokyuubi\PhantomJs\Template\TemplateRenderer;
use neokyuubi\PhantomJs\Template\TemplateRendererInterface;
use neokyuubi\PhantomJs\Procedure\Input;
use neokyuubi\PhantomJs\Procedure\Output;
use neokyuubi\PhantomJs\Procedure\Procedure;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@neokyuubi.me>
 */
class ProcedureTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test procedure template can be
     * set in procedure
     *
     * @access public
     * @return void
     */
    public function testProcedureTemplateCanBeSetInProcedure()
    {
        $template = 'PROCEDURE_TEMPLATE';

        $engne     = $this->getEngine();
        $parser    = $this->getParser();
        $cache     = $this->getCache();
        $renderer  = $this->getRenderer();

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->setTemplate($template);

        $this->assertSame($procedure->getTemplate(), $template);
    }

    /**
     * Test procedure can be compiled.
     *
     * @access public
     * @return void
     */
    public function testProcedureCanBeCompiled()
    {
        $template = 'TEST_{{ input.get("uncompiled") }}_PROCEDURE';

        $engne     = $this->getEngine();
        $parser    = $this->getParser();
        $cache     = $this->getCache();
        $renderer  = $this->getRenderer();

        $input  = $this->getInput();
        $input->set('uncompiled', 'COMPILED');

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->setTemplate($template);

        $this->assertSame('TEST_COMPILED_PROCEDURE', $procedure->compile($input));
    }

    /**
     * Test not writable exception is thrown if procedure
     * script cannot be written to file
     *
     * @access public
     * @return void
     */
    public function testNotWritableExceptionIsThrownIfProcedureScriptCannotBeWrittenToFile()
    {
        $this->setExpectedException('\neokyuubi\PhantomJs\Exception\NotWritableException');

        $engne    = $this->getEngine();
        $parser   = $this->getParser();
        $renderer = $this->getRenderer();

        $cache = $this->getCache('/an/invalid/dir');

        $input  = $this->getInput();
        $output = $this->getOutput();

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->run($input, $output);
    }

    /**
     * Test procedure failed exception is thrown if procedure
     * cannot be run.
     *
     * @access public
     * @return void
     */
    public function testProcedureFailedExceptionIsThrownIfProcedureCannotBeRun()
    {
        $this->setExpectedException('\neokyuubi\PhantomJs\Exception\ProcedureFailedException');

        $parser   = $this->getParser();
        $cache    = $this->getCache();
        $renderer = $this->getRenderer();
        $input    = $this->getInput();
        $output   = $this->getOutput();

        $engne = $this->getEngine();
        $engne->method('getCommand')
            ->will($this->throwException(new \Exception()));

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->run($input, $output);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure instance.
     *
     * @access protected
     * @param  \neokyuubi\PhantomJs\Engine                             $engine
     * @param  \neokyuubi\PhantomJs\Parser\ParserInterface             $parser
     * @param  \neokyuubi\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param  \neokyuubi\PhantomJs\Template\TemplateRendererInterface $renderer
     * @return \neokyuubi\PhantomJs\Procedure\Procedure
     */
    protected function getProcedure(Engine $engine, ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $procedure = new Procedure($engine, $parser, $cacheHandler, $renderer);

        return $procedure;
    }

    /**
     * Get parser.
     *
     * @access protected
     * @return \neokyuubi\PhantomJs\Parser\JsonParser
     */
    protected function getParser()
    {
        $parser = new JsonParser();

        return $parser;
    }

    /**
     * Get cache.
     *
     * @access protected
     * @param  string                            $cacheDir  (default: '')
     * @param  string                            $extension (default: 'proc')
     * @return \neokyuubi\PhantomJs\Cache\FileCache
     */
    protected function getCache($cacheDir = '', $extension = 'proc')
    {
        $cache = new FileCache(($cacheDir ? $cacheDir : sys_get_temp_dir()), 'proc');

        return $cache;
    }

    /**
     * Get template renderer.
     *
     * @access protected
     * @return \neokyuubi\PhantomJs\Template\TemplateRenderer
     */
    protected function getRenderer()
    {
        $twig = new Twig_Environment(
            new Twig_Loader_String()
        );

        $renderer = new TemplateRenderer($twig);

        return $renderer;
    }

    /**
     * Get input
     *
     * @access protected
     * @return \neokyuubi\PhantomJs\Procedure\Input
     */
    protected function getInput()
    {
        $input = new Input();

        return $input;
    }

    /**
     * Get output.
     *
     * @access protected
     * @return \neokyuubi\PhantomJs\Procedure\Output
     */
    protected function getOutput()
    {
        $output = new Output();

        return $output;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get engine
     *
     * @access protected
     * @return \neokyuubi\PhantomJs\Engine
     */
    protected function getEngine()
    {
        $engine = $this->getMock('\neokyuubi\PhantomJs\Engine');

        return $engine;
    }
}
