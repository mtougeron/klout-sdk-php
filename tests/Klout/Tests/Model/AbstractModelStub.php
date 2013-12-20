<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Tests\Model;

use Klout\Model\AbstractModel;

class AbstractModelStub extends AbstractModel
{

    /**
     * A protected var with a method
     *
     * @var String
     */
    protected $myProtectedVarWithMethod = 'myProtectedVarWithMethod';

    /**
     * A protected var without a method
     *
     * @var String
     */
    protected $myProtectedVarWithoutMethod = 'myProtectedVarWithoutMethod';

    /**
     * A private var with a method
     *
     * @var String
     */
    protected $myPrivateVarWithMethod = 'myPrivateVarWithMethod';

    /**
     * A private var without a method
     *
     * @var String
     */
    protected $myPrivateVarWithoutMethod = 'myPrivateVarWithoutMethod';

    /**
     * A public var with a method
     *
     * @var String
     */
    public $myPublicVarWithMethod = 'myPublicVarWithMethod';

    /**
     * A public var without a method
     *
     * @var String
     */
    public $myPublicVarWithoutMethod = 'myPublicVarWithoutMethod';

    /**
     * A protected var that is an object with a toArray method
     *
     * @var \Klout\Tests\Model\AbstractModelStub
     */
    protected $myProtectedObjectVarWithToArrayMethod;

    /**
     *
     * @return string
     */
    public function getMyProtectedVarWithMethod()
    {
        return $this->myProtectedVarWithMethod;
    }

    /**
     *
     * @return string
     */
    public function getMyPrivateVarWithMethod()
    {
        return $this->myPrivateVarWithMethod;
    }

    /**
     *
     * @return string
     */
    public function getMyPublicVarWithMethod()
    {
        return $this->myPublicVarWithMethod;
    }

    /**
     *
     * @return \Klout\Tests\Model\AbstractModelStub
     */
    public function getMyProtectedObjectVarWithToArrayMethod()
    {
        return $this->myProtectedObjectVarWithToArrayMethod;
    }

    /**
     *
     * @param  AbstractModelStub                    $myProtectedObjectVarWithToArrayMethod
     * @return \Klout\Tests\Model\AbstractModelStub
     */
    public function setMyProtectedObjectVarWithToArrayMethod(AbstractModelStub $myProtectedObjectVarWithToArrayMethod)
    {
        $this->myProtectedObjectVarWithToArrayMethod = $myProtectedObjectVarWithToArrayMethod;

        return $this;
    }
}
