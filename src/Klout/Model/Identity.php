<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Model;

use Klout\Collection\Identity as IdentityCollection;
use Klout\Exception\InvalidArgumentException;
use Klout\Model\AbstractModel;

class Identity extends AbstractModel
{

    /**
     * The user's Klout Id
     *
     * @var String
     */
    protected $kloutId;

    /**
     * The name for the network this identity is for
     *
     * @var String
     */
    protected $networkName;

    /**
     * The User Id for the network
     *
     * @var String
     */
    protected $networkUserId;

    /**
     * The constructor
     *
     * @param String $kloutId
     * @param array  $identityData
     */
    public function __construct($kloutId = null, array $identityData = array())
    {
        if (!empty($kloutId) && !empty($identityData)) {
            $this->populate($kloutId, $identityData);
        }
    }

    /**
     * Get the Klout Id
     *
     * @return String
     */
    public function getKloutId()
    {
        return $this->kloutId;
    }

    /**
     * Set the Klout Id
     *
     * @param  String                $kloutId
     * @return \Klout\Model\Identity
     */
    public function setKloutId($kloutId)
    {
        $this->kloutId = $kloutId;

        return $this;
    }

    /**
     * Get the Network's user Id
     *
     * @return String
     */
    public function getNetworkUserId()
    {
        return $this->networkUserId;
    }

    /**
     * Set the Network's user Id
     *
     * @param  String                $networkUserId
     * @return \Klout\Model\Identity
     */
    public function setNetworkUserId($networkUserId)
    {
        $this->networkUserId = $networkUserId;

        return $this;
    }

    /**
     * Get the name of the network
     *
     * @return String
     */
    public function getNetworkName()
    {
        return $this->networkName;
    }

    /**
     * Set the Network's name
     *
     * @param  String                $networkName
     * @return \Klout\Model\Identity
     */
    public function setNetworkName($networkName)
    {
        $this->networkName = $networkName;

        return $this;
    }

    /**
     * Populate the object with an array of data.
     *
     * @param  String                $kloutId
     * @param  array                 $identityData
     * @return \Klout\Model\Identity
     */
    public function populate($kloutId, array $identityData)
    {
        if ((empty($kloutId) && empty($this->kloutId)) || empty($identityData)) {
            return $this;
        }

        $this->setKloutId($kloutId);
        if (isset($identityData['network'])) {
            $this->setNetworkName($identityData['network']);
        }
        if (isset($identityData['id'])) {
            $this->setNetworkUserId($identityData['id']);
        }

        return $this;
    }

}
