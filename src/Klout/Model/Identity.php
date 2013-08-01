<?php

namespace Klout\Model;

use Klout\Exception\InvalidArgumentException;
use Klout\Collection\Identity as IdentityCollection;

class Identity extends AbstractModel
{

    /* @var string - The user's Klout ID */
    protected $kloutId;
    /* @var string - The user's ID for the network */
    protected $networkUserId;

    public function __construct($kloutId = null, array $identityData = null)
    {
        if (!empty($kloutId) && !empty($identityData)) {
            $this->populate($kloutId, $identityData);
        }
    }

    public function getKloutId()
    {
        return $this->kloutId;
    }

    public function setKloutId($kloutId)
    {
        $this->kloutId = $kloutId;

        return $this;
    }

    public function getNetworkUserId()
    {
        return $this->networkUserId;
    }

    public function setNetworkUserId($networkUserId)
    {
        $this->networkUserId = $networkUserId;

        return $this;
    }

    public function getNetworkName()
    {
        return $this->networkName;
    }

    public function setNetworkName($networkName)
    {
        $this->networkName = $networkName;

        return $this;
    }

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
