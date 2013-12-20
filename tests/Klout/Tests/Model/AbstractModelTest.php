<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Tests\Model;

use Klout\Tests\Model\AbstractModelStub;

class AbstractModelTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests that an empty object (no protected/private properties)
     * will still return an array but is empty
     *
     * @covers \Klout\Model\AbstractModel::toArray
     */
    public function testToArrayEmptyObject()
    {
        $class = $this->getMockForAbstractClass('Klout\Model\AbstractModel');
        $results = $class->toArray();
        $this->assertInternalType('array', $results);
        $this->assertCount(0, $results);
    }

    /**
     * Tests that only the protected & private properties of the AbstractModelStub
     * returns data in the array
     *
     * @covers \Klout\Model\AbstractModel::toArray
     */
    public function testToArray()
    {
        $class = new AbstractModelStub();
        // Set an instance of AbstractModelStub so that the recursion happens
        $class->setMyProtectedObjectVarWithToArrayMethod(new AbstractModelStub());
        $results = $class->toArray();

        $this->assertInternalType('array', $results);
        $this->assertCount(3, $results);
        $this->assertArrayHasKey('myProtectedVarWithMethod', $results);
        $this->assertArrayHasKey('myPrivateVarWithMethod', $results);
        $this->assertArrayHasKey('myProtectedObjectVarWithToArrayMethod', $results);

        $this->assertInternalType('array', $results['myProtectedObjectVarWithToArrayMethod']);
        $this->assertCount(3, $results['myProtectedObjectVarWithToArrayMethod']);
        $this->assertArrayHasKey('myProtectedVarWithMethod', $results['myProtectedObjectVarWithToArrayMethod']);
        $this->assertArrayHasKey('myPrivateVarWithMethod', $results['myProtectedObjectVarWithToArrayMethod']);
        $this->assertArrayHasKey('myProtectedObjectVarWithToArrayMethod', $results['myProtectedObjectVarWithToArrayMethod']);

        // The recursion should set this property but it doesn't have a value set
        $this->assertNull($results['myProtectedObjectVarWithToArrayMethod']['myProtectedObjectVarWithToArrayMethod']);

    }

}
