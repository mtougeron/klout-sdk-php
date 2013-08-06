<?php

namespace Klout\Model;

use Klout\Collection\Identity as IdentityCollection;
use Klout\Exception\InvalidArgumentException;
use Klout\Model\AbstractModel;

class Identity extends AbstractModel
{

    /* @var string - The user's Klout ID */
    protected $kloutId;

    /* @var string - The user's ID for the network */
    protected $networkUserId;

    /**
     * The constructor
     *
     * @param String $kloutId
     * @param array  $identityData
     */
    public function __construct($kloutId = null, array $identityData = null)
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
        $this->setNetworkName($identityData['network']);
        $this->setNetworkUserId($identityData['id']);

        return $this;
    }

    /**
     * Create an identity collection based on an array of identities
     *
     * @param  array                      $identityArray
     * @throws InvalidArgumentException
     * @return \Klout\Collection\Identity
     */
    public static function createIdentityCollection(array $identityArray)
    {
        $identities = new IdentityCollection();
        if (empty($identityArray)) {
            return $identities;
        }

        foreach ($identityArray as $identityData) {
            $identity = new self($identityData);
            if (!$identity->getKloutId()) {
                throw new InvalidArgumentException('Invalid identity data.');
            }
            $identities[$identity->getNetworkName()] = $identity;
        }

        return $identities;
    }

}
