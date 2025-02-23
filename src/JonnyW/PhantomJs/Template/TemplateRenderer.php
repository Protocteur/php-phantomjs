<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace protocteur\PhantomJs\Template;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@protocteur.me>
 */
class TemplateRenderer implements TemplateRendererInterface
{
    /**
     * Twig environment instance.
     *
     * @var \Twig\Environment
     * @access protected
     */
    protected $twig;

    /**
     * Internal constructor.
     *
     * @access public
     * @param \Twig\Environment $twig
     */
    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render template.
     *
     * @access public
     * @param  string $template
     * @param  array  $context  (default: array())
     * @return string
     */
    public function render($template, array $context = array())
    {
        $template = $this->twig->createTemplate($template);

        return $template->render($context);
    }
}
