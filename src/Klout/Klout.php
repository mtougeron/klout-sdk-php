<?php

namespace Klout;

use Klout\Exception\InvalidArgumentException;
use Klout\Exception\ResourceNotFoundException;
use Klout\Exception\NotAuthorizedException;
use Klout\Exception\ServiceUnavailableException;

use Klout\Model\User;
use Klout\Model\Identity;
use Klout\Model\Score;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Exception\MultiTransferException;
use Guzzle\Http\Exception\RequestException as HttpRequestException;

class Klout
{
    const NETWORK_KLOUT = 'ks';
    const NETWORK_TWITTER = 'twitter';
    const NETWORK_TWITTER_ID = 'tw';
    const NETWORK_GOOGLE_PLUS = 'gp';
    const NETWORK_INSTAGRAM = 'ig';

    protected $apiKey;

    protected $apiBaseUri = 'http://api.klout.com/v2';

    /* @var Guzzle\Http\Client */
    protected $client;

    public function __construct($apiKey, $apiBaseUri = null)
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('You must specify your API key.');
        }

        if (!empty($apiBaseUri)) {
            $this->apiBaseUri = $apiBaseUri;
        }

        $this->createClient();

        $this->setApiKey($apiKey);
    }

    protected function createClient()
    {
        $client = new Client($this->apiBaseUri);
        $client->setUserAgent(__CLASS__, true);
        $this->setClient($client);
    }

    public function setApiBaseUri($apiBaseUri)
    {
        if (empty($apiBaseUri)) {
            throw new InvalidArgumentException('Invalid API base uri.');
        }
        $this->apiBaseUri;
        $this->createClient();

        return $this;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client->setDefaultOption('query', array('key' => $apiKey));

        return $this;
    }

    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this->client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getUserByTwitterUsername($username, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_TWITTER, $username, $fullData);
    }

    public function getUserByTwitterId($userId, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_TWITTER_ID, $userId, $fullData);
    }

    public function getUserByGooglePlusId($userId, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_GOOGLE_PLUS, $userId, $fullData);
    }

    public function getUserByInstagramId($userId, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_INSTAGRAM, $userId, $fullData);
    }

    public function getTwitterId($kloutId)
    {

        $kloutId = trim($kloutId);
        if (empty($kloutId)) {
            throw new InvalidArgumentException('Missing Klout ID.');
        }
        /* @var $request Request */
        $request = $this->client->get(
            'identity.json/klout/' . $kloutId . '/tw'
        );

        try {
            $identityData = $request->send()->json();
            $identity = new Identity();
            $identity->setKloutId($kloutId);
            $identity->setNetworkName(self::NETWORK_TWITTER_ID);
            $identity->setNetworkUserId($identityData['id']);

        } catch (HttpRequestException $e) {
            $this->handleHttpRequestException($e);
        }

        return $identity;
    }

    public function getScore($kloutId)
    {

        $kloutId = trim($kloutId);
        if (empty($kloutId)) {
            throw new InvalidArgumentException('Missing Klout ID.');
        }

        /* @var $request Request */
        $request = $this->client->get(
            'user.json/' . $kloutId . '/score'
        );

        try {
            $scoreData = $request->send()->json();
            $score = new Score();
            $score->populate($kloutId, $scoreData);

        } catch (HttpRequestException $e) {
            $this->handleHttpRequestException($e);
        }

        return $score;
    }

    public function getUserByNetwork($networkName, $networkUserId, $fullData = true)
    {
        $networkName = trim($networkName);
        $networkUserId = trim($networkUserId);
        if (empty($networkName)) {
            throw new InvalidArgumentException('Missing network name.');
        } elseif (empty($networkUserId)) {
            throw new InvalidArgumentException('Missing network user id.');
        }
        $networkName = urlencode(strtolower($networkName));

        $idString = '';
        $queryParams = array();
        if ($networkName != self::NETWORK_TWITTER) {
            $idString = $networkUserId;
        } else {
            $queryParams['screenName'] = $networkUserId;
        }

        /* @var $request Request */
        $request = $this->client->get(
            'identity.json/' . $networkName . '/' . $idString,
            null,
            array(
                'query' => $queryParams
            )
        );

        try {
            $identityData = $request->send()->json();
            $identity = new Identity();
            $identity->setKloutId($identityData['id']);
            $identity->setNetworkName($networkName);
            $identity->setNetworkUserId($networkUserId);

            $user = $this->getUser($identity->getKloutId(), $fullData);
            $user->addIdentity($identity);
        } catch (HttpRequestException $e) {
            $this->handleHttpRequestException($e);
        }

        return $user;
    }

    public function getUser($kloutId, $fullData = true)
    {
        $kloutId = trim($kloutId);
        if (empty($kloutId)) {
            throw new InvalidArgumentException('Missing Klout ID.');
        }

        try {
            // Get the data for the user
            $requests = array(
                $this->client->get(
                    'user.json/' . $kloutId
                )
            );
            if ($fullData) {
                $requests[] = $this->client->get(
                    'user.json/' . $kloutId . '/influence'
                );
                $requests[] = $this->client->get(
                    'user.json/' . $kloutId . '/topics'
                );
            }

            $responses = $this->client->send($requests);
        } catch (HttpRequestException $e) {
            // If there are partial responses then the method
            // will return those responses
            $responses = $this->handleHttpRequestException($e);
        }

        $influenceData = array();
        $topicsData = array();
        $userData = array();
        /* @var $response Response */
        foreach ($responses as $response) {
            if (stripos($response->getEffectiveUrl(), '/influence') !== false) {
                $influenceData = $response->json();
            } elseif (stripos($response->getEffectiveUrl(), '/topics') !== false) {
                $topicsData = $response->json();
            } else {
                $userData = $response->json();
            }
        }

        if (empty($userData)) {
            throw new ResourceNotFoundException('Could not find the user information for klout id: ' . $identity->getKloutId());
        }

        $user = new User();
        $user->populate($userData, $influenceData, $topicsData);

        return $user;
    }

    protected function handleHttpRequestException(HttpRequestException $e)
    {

        if ($e instanceof ClientErrorResponseException) {
            switch ($e->getResponse()->getStatusCode()) {
                case 403;
                    throw new NotAuthorizedException($e->getMessage());
                    break;
                case 404;
                    throw new ResourceNotFoundException($e->getMessage());
                    break;
            }
        } elseif ($e instanceof ServerErrorResponseException) {
            throw new ServiceUnavailableException($e->getMessage());
        } elseif ($e instanceof MultiTransferException) {
            if (count($e->getSuccessfulRequests()) == 0) {
                throw new ResourceNotFoundException($e->getMessage());
            }

            $responses = array();
            foreach ($e->getSuccessfulRequests() as $request) {
                $responses[] = $request->getResponse();
            }

            // In this case we return the valid responses
            return $responses;
        }

        // If we don't transform it to a Klout\Exception then
        // rethrow the original exception
        throw $e;
    }

}
