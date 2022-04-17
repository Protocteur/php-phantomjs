<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace neokyuubi\PhantomJs\Procedure;

use neokyuubi\PhantomJs\Engine;
use neokyuubi\PhantomJs\Cache\CacheInterface;
use neokyuubi\PhantomJs\Parser\ParserInterface;
use neokyuubi\PhantomJs\Template\TemplateRendererInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@neokyuubi.me>
 */
class ProcedureFactory implements ProcedureFactoryInterface
{
    /**
     * PhantomJS engine
     *
     * @var \neokyuubi\PhantomJs\Engine
     * @access protected
     */
    protected $engine;

    /**
     * Parser.
     *
     * @var \neokyuubi\PhantomJs\Parser\ParserInterface
     * @access protected
     */
    protected $parser;

    /**
     * Cache handler.
     *
     * @var \neokyuubi\PhantomJs\Cache\CacheInterface
     * @access protected
     */
    protected $cacheHandler;

    /**
     * Template renderer.
     *
     * @var \neokyuubi\PhantomJs\Template\TemplateRendererInterface
     * @access protected
     */
    protected $renderer;

    /**
     * Internal constructor.
     *
     * @access public
     * @param \neokyuubi\PhantomJs\Engine                             $engine
     * @param \neokyuubi\PhantomJs\Parser\ParserInterface             $parser
     * @param \neokyuubi\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param \neokyuubi\PhantomJs\Template\TemplateRendererInterface $renderer
     */
    public function __construct(Engine $engine, ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $this->engine       = $engine;
        $this->parser       = $parser;
        $this->cacheHandler = $cacheHandler;
        $this->renderer     = $renderer;
    }

    /**
     * Create new procedure instance.
     *
     * @access public
     * @return \neokyuubi\PhantomJs\Procedure\Procedure
     */
    public function createProcedure()
    {
        $procedure = new Procedure(
            $this->engine,
            $this->parser,
            $this->cacheHandler,
            $this->renderer
        );

        return $procedure;
    }
}
