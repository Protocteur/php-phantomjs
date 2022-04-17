<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace neokyuubi\PhantomJs\Procedure;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@neokyuubi.me>
 */
interface ProcedureFactoryInterface
{
    /**
     * Create new procedure instance.
     *
     * @access public
     * @return \neokyuubi\PhantomJs\Procedure\ProcedureInterface
     */
    public function createProcedure();
}
