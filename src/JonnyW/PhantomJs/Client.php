<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace neokyuubi\PhantomJs;

use neokyuubi\PhantomJs\Procedure\ProcedureLoaderInterface;
use neokyuubi\PhantomJs\Procedure\ProcedureCompilerInterface;
use neokyuubi\PhantomJs\Http\MessageFactoryInterface;
use neokyuubi\PhantomJs\Http\RequestInterface;
use neokyuubi\PhantomJs\Http\ResponseInterface;
use neokyuubi\PhantomJs\DependencyInjection\ServiceContainer;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@neokyuubi.me>
 */
class Client implements ClientInterface
{
    /**
     * Client.
     *
     * @var \neokyuubi\PhantomJs\ClientInterface
     * @access private
     */
    private static $instance;

    /**
     * PhantomJs engine.
     *
     * @var \neokyuubi\PhantomJs\Engine
     * @access protected
     */
    protected $engine;

    /**
     * Procedure loader.
     *
     * @var \neokyuubi\PhantomJs\Procedure\ProcedureLoaderInterface
     * @access protected
     */
    protected $procedureLoader;

    /**
     * Procedure validator.
     *
     * @var \neokyuubi\PhantomJs\Procedure\ProcedureCompilerInterface
     * @access protected
     */
    protected $procedureCompiler;

    /**
     * Message factory.
     *
     * @var \neokyuubi\PhantomJs\Http\MessageFactoryInterface
     * @access protected
     */
    protected $messageFactory;

    /**
     * Procedure template
     *
     * @var string
     * @access protected
     */
    protected $procedure;

    /**
     * Internal constructor
     *
     * @access public
     * @param  \neokyuubi\PhantomJs\Engine                               $engine
     * @param  \neokyuubi\PhantomJs\Procedure\ProcedureLoaderInterface   $procedureLoader
     * @param  \neokyuubi\PhantomJs\Procedure\ProcedureCompilerInterface $procedureCompiler
     * @param  \neokyuubi\PhantomJs\Http\MessageFactoryInterface         $messageFactory
     * @return void
     */
    public function __construct(Engine $engine, ProcedureLoaderInterface $procedureLoader, ProcedureCompilerInterface $procedureCompiler, MessageFactoryInterface $messageFactory)
    {
        $this->engine            = $engine;
        $this->procedureLoader   = $procedureLoader;
        $this->procedureCompiler = $procedureCompiler;
        $this->messageFactory    = $messageFactory;
        $this->procedure         = 'http_default';
    }

    /**
     * Get singleton instance
     *
     * @access public
     * @return \neokyuubi\PhantomJs\Client
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof ClientInterface) {

            $serviceContainer = ServiceContainer::getInstance();

            self::$instance = new static(
                $serviceContainer->get('engine'),
                $serviceContainer->get('procedure_loader'),
                $serviceContainer->get('procedure_compiler'),
                $serviceContainer->get('message_factory')
            );
        }

        return self::$instance;
    }

    /**
     * Get PhantomJs engine.
     *
     * @access public
     * @return \neokyuubi\PhantomJs\Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Get message factory instance
     *
     * @access public
     * @return \neokyuubi\PhantomJs\Http\MessageFactoryInterface
     */
    public function getMessageFactory()
    {
        return $this->messageFactory;
    }

    /**
     * Get procedure loader instance
     *
     * @access public
     * @return \neokyuubi\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    public function getProcedureLoader()
    {
        return $this->procedureLoader;
    }

    /**
     * Send request
     *
     * @access public
     * @param  \neokyuubi\PhantomJs\Http\RequestInterface  $request
     * @param  \neokyuubi\PhantomJs\Http\ResponseInterface $response
     * @return \neokyuubi\PhantomJs\Http\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response)
    {
        $procedure = $this->procedureLoader->load($this->procedure);

        $this->procedureCompiler->compile($procedure, $request);

        $procedure->run($request, $response);

        return $response;
    }

    /**
     * Get log.
     *
     * @access public
     * @return string
     */
    public function getLog()
    {
        return $this->getEngine()->getLog();
    }

    /**
     * Set procedure template.
     *
     * @access public
     * @param  string $procedure
     * @return void
     */
    public function setProcedure($procedure)
    {
        $this->procedure = $procedure;
    }

    /**
     * Get procedure template.
     *
     * @access public
     * @return string
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Get procedure compiler.
     *
     * @access public
     * @return \neokyuubi\PhantomJs\Procedure\ProcedureCompilerInterface
     */
    public function getProcedureCompiler()
    {
        return $this->procedureCompiler;
    }

    /**
     * Set lazy request flag.
     *
     * @access public
     * @return void
     */
    public function isLazy()
    {
        $this->procedure = 'http_lazy';
    }
}
