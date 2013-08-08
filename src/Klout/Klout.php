<?php

namespace Klout;

use Klout\Exception\InvalidArgumentException;
use Klout\Exception\NotAuthorizedException;
use Klout\Exception\ResourceNotFoundException;
use Klout\Exception\ServiceUnavailableException;

use Klout\Model\Identity;
use Klout\Model\Score;
use Klout\Model\User;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\MultiTransferException;
use Guzzle\Http\Exception\RequestException as HttpRequestException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;

use Zend\Uri\Uri;
use Zend\Validator\Digits as ValidatorDigits;
use Zend\Validator\Uri as ValidatorUri;

class Klout
{

    /**
     * Constants for the different networks Klout supports
     */
    const NETWORK_KLOUT = 'ks';
    const NETWORK_TWITTER = 'twitter';
    const NETWORK_TWITTER_ID = 'tw';
    const NETWORK_GOOGLE_PLUS = 'gp';
    const NETWORK_INSTAGRAM = 'ig';

    /**
     * The apiKey for the application
     *
     * @var String
     */
    protected $apiKey;

    /**
     * The base uri for calling the Klout API
     *
     * @var String
     */
    protected $apiBaseUri = 'http://api.klout.com/v2';

    /**
     * Used to make the calls to the API
     *
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * Constructor
     *
     * @param  String                   $apiKey
     * @param  String:\Zend\Uri\Uri     $apiBaseUri
     * @throws InvalidArgumentException
     */
    public function __construct($apiKey, $apiBaseUri = null)
    {
        if (empty($apiKey)) {
            throw new InvalidArgumentException('You must specify your API key.');
        }

        if (!empty($apiBaseUri)) {
            $this->setApiBaseUri($apiBaseUri);
        }

        $this->createClient();

        $this->setApiKey($apiKey);
    }

    /**
     * Create the default Client
     *
     * @return \Klout\Klout
     */
    protected function createClient()
    {
        $client = new Client($this->apiBaseUri);
        $client->setUserAgent(__CLASS__, true);
        $this->setClient($client);

        return $this;
    }

    /**
     *
     * @param  String:\Zend\Uri\Uri      $apiBaseUri
     * @throws InvalidArgumentException
     * @return \Klout\Klout
     */
    public function setApiBaseUri($apiBaseUri)
    {
        if (empty($apiBaseUri)) {
            throw new InvalidArgumentException('apiBaseUri cannot be empty().');
        }

        if ($apiBaseUri instanceof Uri) {
            // Convert the object to a string if necessary
            $apiBaseUri = $apiBaseUri->toString();
        } elseif (!is_string($apiBaseUri)) {
            throw new InvalidArgumentException('apiBaseUri must be a String or \\Zend\\Uri\\Uri.');
        }

        // Validate the apiBaseUri
        $validator = new ValidatorUri();
        $validator->setAllowRelative(false);
        if (!$validator->isValid($apiBaseUri)) {
            $errors = $validator->getMessages();
            $errorMessage = reset($errors);
            throw new InvalidArgumentException('apiBaseUri is invalid: ' . $errorMessage);
        }

        $this->apiBaseUri = $apiBaseUri;

        // Only create the client if one already exists.
        if (isset($this->client)) {
            $this->createClient();
        }

        return $this;
    }

    /**
     * Set the apiKey
     *
     * @param  String       $apiKey
     * @return \Klout\Klout
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client->setDefaultOption('query', array('key' => $apiKey));

        return $this;
    }

    /**
     * Set the Client for the class to use. This is so that you can manipulate your
     * own instance of Guzzle\Http\Client and set it to be used.
     *
     * @param  \Guzzle\Http\Client $client
     * @return \Klout\Klout
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the current Client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get a Klout User by a Twitter username
     *
     * @param  String            $username
     * @param  Boolean             $fullData
     * @return \Klout\Model\User
     */
    public function getUserByTwitterUsername($username, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_TWITTER, $username, $fullData);
    }

    /**
     * Get a Klout User by a Twitter user ID
     *
     * @param  Numeric           $userId
     * @param  Boolean           $fullData
     * @return \Klout\Model\User
     */
    public function getUserByTwitterId($userId, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_TWITTER_ID, $userId, $fullData);
    }

    /**
     * Get a Klout User by a Google+ user ID
     *
     * @param  Numeric           $userId
     * @param  Boolean             $fullData
     * @return \Klout\Model\User
     */
    public function getUserByGooglePlusId($userId, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_GOOGLE_PLUS, $userId, $fullData);
    }

    /**
     * Get a Klout User by an Instagram user ID
     *
     * @param  Numeric           $userId
     * @param  Boolean             $fullData
     * @return \Klout\Model\User
     */
    public function getUserByInstagramUserId($userId, $fullData = true)
    {
        return $this->getUserByNetwork(self::NETWORK_INSTAGRAM, $userId, $fullData);
    }

    /**
     * Get a Twitter Identity for a Klout User ID
     *
     * @param  Numeric               $kloutId
     * @return \Klout\Model\Identity
     */
    public function getTwitterIdentity($kloutId)
    {
        $this->assertValidUserIdForNetwork(self::NETWORK_KLOUT, $kloutId);

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

    /**
     * Get the Klout user's score
     *
     * @param  Numeric            $kloutId
     * @return \Klout\Model\Score
     */
    public function getScoreByKloutId($kloutId)
    {
        $this->assertValidUserIdForNetwork(self::NETWORK_KLOUT, $kloutId);

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

    /**
     * Get a Klout User by their network ID
     *
     * @param  String                   $networkName
     * @param  String                   $networkUserId
     * @param  Boolean                    $fullData
     * @throws InvalidArgumentException
     * @return \Klout\Model\User
     */
    public function getUserByNetwork($networkName, $networkUserId, $fullData = true)
    {
        $this->assertValidNetworkName($networkName);
        $this->assertValidUserIdForNetwork($networkName, $networkUserId);

        $idString = '';
        $queryParams = array();
        // The calls to the API based on the Twitter sceenName (username)
        // is different from all the other network based ID lookups
        switch ($networkName) {
            case self::NETWORK_TWITTER:
                $queryParams['screenName'] = $networkUserId;
                break;
            default:
                $idString = $networkUserId;
                break;
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

    /**
     * Get a Klout\Model\User by their Klout ID.
     *
     * @param  String                    $kloutId
     * @param  Boolean                     $fullData
     * @throws InvalidArgumentException
     * @throws ResourceNotFoundException
     * @return \Klout\Model\User
     */
    public function getUser($kloutId, $fullData = true)
    {
        $this->assertValidUserIdForNetwork(self::NETWORK_KLOUT, $kloutId);

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
            throw new ResourceNotFoundException('Could not find the user information for Klout user ID: ' . $kloutId);
        }

        $user = new User();
        $user->populate($userData, $influenceData, $topicsData);

        $identity = new Identity();
        $identity->setKloutId($kloutId);
        $identity->setNetworkName(self::NETWORK_KLOUT);
        $identity->setNetworkUserId($kloutId);
        $user->addIdentity($identity);

        return $user;
    }

    /**
     * Transforms the exceptions thrown by \Guzzle\Http\Client
     * into Klout specific ones. If it doesn't know how to handle
     * the exception then it just re-throws it.
     *
     * @param  HttpRequestException        $e
     * @throws NotAuthorizedException
     * @throws ResourceNotFoundException
     * @throws ServiceUnavailableException
     * @throws Ambigous                    <HttpRequestException, \Guzzle\Http\Exception\ClientErrorResponseException, \Guzzle\Http\Exception\ServerErrorResponseException, \Guzzle\Http\Exception\MultiTransferException>
     * @return array:NULL
     */
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

    /**
     * Check to make sure the networkName is valid.
     *
     * @param  String                   $networkName
     * @throws InvalidArgumentException
     */
    protected function assertValidNetworkName($networkName)
    {
        if (!preg_match('/^[A-Za-z0-9]*$/', $networkName)) {
            throw new InvalidArgumentException("'$networkName'" . ' is not a valid network name.');
        }
    }

    /**
     * Check to make sure the networkUserId is valid
     *
     * @param  String                   $networkName
     * @param  String                   $networkUserId
     * @throws InvalidArgumentException
     */
    protected function assertValidUserIdForNetwork($networkName, $networkUserId)
    {
        $this->assertValidNetworkName($networkName);

        switch ($networkName) {
            case self::NETWORK_GOOGLE_PLUS:
            case self::NETWORK_KLOUT:
            case self::NETWORK_TWITTER_ID:
            case self::NETWORK_INSTAGRAM:
                // These networks only allow numeric userIds
                $validator = new ValidatorDigits();
                if (!$validator->isValid($networkUserId)) {
                    throw new InvalidArgumentException("'$networkUserId'" . ' is not a valid network user ID.');
                }
                break;
            default:
                if (!preg_match('/^[A-Za-z0-9]*$/', $networkUserId)) {
                    throw new InvalidArgumentException("'$networkUserId'" . ' is not a valid network user ID.');
                }
                break;
        }
    }

}
