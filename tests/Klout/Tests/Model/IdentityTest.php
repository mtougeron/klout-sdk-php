<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Tests\Model;

use Klout\Klout;
use Klout\Model\Identity;

class IdentityTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The kloutId for testing
     *
     * @var String
     */
    protected $kloutId = '615089';

    /**
     * The identity data array for testing
     *
     * @var array
     */
    protected $identityData = array('network' => 'abc123', 'id' => '123456');

    /**
     * @covers \Klout\Model\Identity::__construct
     */
    public function testNewEmptyIdentity()
    {
        $identity = new Identity();
        $this->assertNull($identity->getKloutId());
        $this->assertNull($identity->getNetworkName());
        $this->assertNull($identity->getNetworkUserId());
    }

    /**
     * @covers \Klout\Model\Identity::__construct
     */
    public function testNewIdentityWithKloutIdButNotIdentityData()
    {
        $identity = new Identity($this->kloutId);
        $this->assertNull($identity->getKloutId());
        $this->assertNull($identity->getNetworkName());
        $this->assertNull($identity->getNetworkUserId());
    }

    /**
     * @covers \Klout\Model\Identity::__construct
     */
    public function testNewIdentityWithIdentityDataButNotKloutId()
    {
        $identity = new Identity(null, $this->identityData);
        $this->assertNull($identity->getKloutId());
        $this->assertNull($identity->getNetworkName());
        $this->assertNull($identity->getNetworkUserId());
    }

    /**
     * @covers \Klout\Model\Identity::__construct
     * @covers \Klout\Model\Identity::populate
     */
    public function testNewIdentityWithData()
    {
        $identity = new Identity($this->kloutId, $this->identityData);
        $this->assertEquals($this->kloutId, $identity->getKloutId());
        $this->assertEquals($this->identityData['network'], $identity->getNetworkName());
        $this->assertEquals($this->identityData['id'], $identity->getNetworkUserId());
    }

    /**
     * @covers \Klout\Model\Identity::setKloutId
     * @covers \Klout\Model\Identity::getKloutId
     */
    public function testGetKloutId()
    {
        $identity = new Identity();
        $this->assertNull($identity->getKloutId());
        $identity->setKloutId($this->kloutId);
        $this->assertEquals($this->kloutId, $identity->getKloutId());
    }

    /**
     * @covers \Klout\Model\Identity::setNetworkUserId
     * @covers \Klout\Model\Identity::getNetworkUserId
     */
    public function testGetNetworkUserId()
    {
        $identity = new Identity();
        $this->assertNull($identity->getNetworkUserId());
        $identity->setNetworkUserId($this->identityData['id']);
        $this->assertEquals($this->identityData['id'], $identity->getNetworkUserId());
    }

    /**
     * @covers \Klout\Model\Identity::setNetworkName
     * @covers \Klout\Model\Identity::getNetworkName
     */
    public function testGetNetworkName()
    {
        $identity = new Identity();
        $this->assertNull($identity->getNetworkName());
        $identity->setNetworkName($this->identityData['network']);
        $this->assertEquals($this->identityData['network'], $identity->getNetworkName());
    }
}
