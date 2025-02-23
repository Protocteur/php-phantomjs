<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace protocteur\PhantomJs\Http;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@protocteur.me>
 */
interface MessageFactoryInterface
{
    /**
     * Get singleton instance.
     *
     * @access public
     * @return \protocteur\PhantomJs\Http\MessageFactoryInterface
     */
    public static function getInstance();

    /**
     * Create request instance.
     *
     * @access public
     * @param  string                                  $url     (default: null)
     * @param  string                                  $method  (default: RequestInterface::METHOD_GET)
     * @param  int                                     $timeout (default: 5000)
     * @return \protocteur\PhantomJs\Http\RequestInterface
     */
    public function createRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000);

    /**
     * Create capture request instance.
     *
     * @access public
     * @param  string                                  $url     (default: null)
     * @param  string                                  $method  (default: RequestInterface::METHOD_GET)
     * @param  int                                     $timeout (default: 5000)
     * @return \protocteur\PhantomJs\Http\RequestInterface
     */
    public function createCaptureRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000);

    /**
     * Create PDF request instance.
     *
     * @access public
     * @param  string                            $url
     * @param  string                            $method
     * @param  int                               $timeout
     * @return \protocteur\PhantomJs\Http\PdfRequest
     */
    public function createPdfRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000);

    /**
     * Create response instance.
     *
     * @access public
     * @return \protocteur\PhantomJs\Http\ResponseInterface
     */
    public function createResponse();
}
