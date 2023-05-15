<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace protocteur\PhantomJs\Tests\Integration\Procedure;

use Symfony\Component\Config\FileLocator;
use protocteur\PhantomJs\Client;
use protocteur\PhantomJs\Procedure\Procedure;
use protocteur\PhantomJs\Procedure\ProcedureLoaderInterface;
use protocteur\PhantomJs\Procedure\ProcedureValidator;
use protocteur\PhantomJs\Validator\Esprima;
use protocteur\PhantomJs\Validator\EngineInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@protocteur.me>
 */
class ProcedureValidatorTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test syntax exception is
     * thrown if procedure contains
     * syntax error.
     *
     * @access public
     * @return void
     */
    public function testProcedureSyntaxExceptionIsThrownIfProcedureContainsSyntaxError()
    {
        $this->setExpectedException('\protocteur\PhantomJs\Exception\SyntaxException');

        $procedureLoader = $this->getProcedureLoader();
        $esprima         = $this->getEsprima();

        $validator = $this->getValidator($procedureLoader, $esprima);
        $validator->validate('return false; var');
    }

    /**
     * Test syntax exception contains errors.
     *
     * @access public
     * @return void
     */
    public function testSyntaxExceptionContainsErrors()
    {
        $procedureLoader = $this->getProcedureLoader();
        $esprima         = $this->getEsprima();

        try {

            $validator = $this->getValidator($procedureLoader, $esprima);
            $validator->validate('return false; var');

        } catch (\protocteur\PhantomJs\Exception\SyntaxException $e) {
            $this->assertNotEmpty($e->getErrors());
        }
    }

    /**
     * Test requirement exception is thrown
     * if procedure does not contain phantom
     * exit statement.
     *
     * @access public
     * @return void
     */
    public function testRequirementExceptionIsThrownIfProcedureDoesNotContainPhanomtExitStatement()
    {
        $this->setExpectedException('\protocteur\PhantomJs\Exception\RequirementException');

        $procedureLoader = $this->getProcedureLoader();
        $esprima         = $this->getEsprima();

        $validator = $this->getValidator($procedureLoader, $esprima);
        $validator->validate('var test = function () { console.log("ok"); }');
    }

    /**
     * Test true is returned if procedure is valid
     *
     * @access public
     * @return void
     */
    public function testTrueIsReturnedIfProcedureIsValid()
    {
        $procedureLoader = $this->getProcedureLoader();
        $esprima         = $this->getEsprima();

        $validator = $this->getValidator($procedureLoader, $esprima);

        $this->assertTrue($validator->validate('var test = function () { console.log("ok"); }; phantom.exit(1);'));
    }

    /**
     * Test procedure is valid if procedure
     * has comments.
     *
     * @access public
     * @return void
     */
    public function testProcedureIsValidIfProcedureHasComments()
    {
        $procedureLoader = $this->getProcedureLoader();
        $esprima         = $this->getEsprima();

        $validator = $this->getValidator($procedureLoader, $esprima);

        $this->assertTrue($validator->validate('/** * Test comment **/ var test = function () { console.log("ok"); }; phantom.exit(1);'));
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure validator.
     *
     * @access protected
     * @param  \protocteur\PhantomJs\Procedure\ProcedureLoaderInterface $procedureLoader
     * @param  \protocteur\PhantomJs\Validator\EngineInterface          $engine
     * @return \protocteur\PhantomJs\Procedure\ProcedureValidator
     */
    protected function getValidator(ProcedureLoaderInterface $procedureLoader, EngineInterface $engine)
    {
        $validator = new ProcedureValidator($procedureLoader, $engine);

        return $validator;
    }

    /**
     * Get procedure loader.
     *
     * @access protected
     * @return \protocteur\PhantomJs\Procedure\ProcedureLoader
     */
    protected function getProcedureLoader()
    {
        return  Client::getInstance()->getProcedureLoader();
    }

    /**
     * Get esprima.
     *
     * @access protected
     * @return \protocteur\PhantomJs\Validator\Esprima
     */
    protected function getEsprima()
    {
        $fileLocator = new FileLocator(
            sprintf('%s/../../../Resources/validators/', __DIR__)
        );

        $esprima = new Esprima($fileLocator, 'esprima-2.0.0.js');

        return $esprima;
    }
}
