<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Model;

use Klout;
use Klout\Collection\Identity as IdentityCollection;
use Klout\Collection\Topic as TopicCollection;
use Klout\Collection\User as UserCollection;
use Klout\Exception\InvalidArgumentException;
use Klout\Model\AbstractModel;
use Klout\Model\Identity;
use Klout\Model\Score;
use Klout\Model\Topic;

class User extends AbstractModel
{

    /**
     * The user's Klout Id
     *
     * @var String
     */
    protected $kloutId;

    /**
     * The identities for the user
     *
     * @var \Klout\Collection\Identity
     */
    protected $identities;

    /**
     * The user's nickname (nick)
     * @var String
     */
    protected $nickname;

    /**
     * The user's score
     *
     * @var \Klout\Model\Score
     */
    protected $score;

    /**
     * The users who influence this user
     *
     * @var \Klout\Collection\User
     */
    protected $influencers;

    /**
     * The users who are influenced by this user
     *
     * @var \Klout\Collection\User
     */
    protected $influencees;

    /**
     * The topics for this user
     *
     * @var \Klout\Collection\Topic
     */
    protected $topics;

    /**
     * The constructor
     *
     * @param array $userData      (optional)
     * @param array $influenceData (optional)
     * @param array $topicsData    (optional)
     */
    public function __construct(array $userData = null, array $influenceData = null, array $topicsData = null)
    {

        // Initialize these variables to the collection
        // so that they can always be operated on.
        $this->setIdentities(new IdentityCollection());
        $this->setInfluencees(new UserCollection());
        $this->setInfluencers(new UserCollection());
        $this->setTopics(new TopicCollection());

        if (empty($userData) && (!empty($influenceData) || !empty($topicsData))) {
            throw new InvalidArgumentException('Must have userData if you have influence or topic data.');
        }

        if (!empty($userData)) {
            $this->populate($userData, $influenceData, $topicsData);
        }
    }

    /**
     * The Klout Id for the User
     *
     * @param  String            $kloutId
     * @return \Klout\Model\User
     */
    public function setKloutId($kloutId)
    {
        $this->kloutId = $kloutId;

        return $this;
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
     * Set the Collection of identities
     *
     * @param  \Klout\Collection\Identity $identities
     * @return \Klout\Model\User
     */
    public function setIdentities(IdentityCollection $identities)
    {
        $this->identities = $identities;

        return $this;
    }

    /**
     * Get the Collection of identities
     *
     * @return \Klout\Collection\Identity
     */
    public function getIdentities()
    {
        return $this->identities;
    }

    /**
     * Get a specific identity from the Collection of
     * identities that this user has.
     *
     * @param  String                     $network
     * @return \Klout\Collection\Identity
     */
    public function getIdentity($network = Klout::NETWORK_KLOUT)
    {
        return $this->identities[$network];
    }

    /**
     * Add a new Identity to the Collection of identities for the user
     *
     * @param  Klout\Model\Identity $identity
     * @return \Klout\Model\User
     */
    public function addIdentity(Klout\Model\Identity $identity)
    {
        $this->identities[$identity->getNetworkName()] = $identity;

        return $this;
    }

    /**
     * Get the nickname (nick) for the user
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set the nickname (nick) for the user
     *
     * @param  String            $nickname
     * @return \Klout\Model\User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get the Score for the user
     *
     * @return \Klout\Model\Score
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set the Score for the user
     *
     * @param  \Klout\Model\Score $score
     * @return \Klout\Model\User
     */
    public function setScore(Klout\Model\Score $score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get the Collection of User(s) who influence this user
     *
     * @return \Klout\Collection\User
     */
    public function getInfluencers()
    {
        return $this->influencers;
    }

    /**
     * Set the Collection of User(s) who influence this user
     *
     * @param  \Klout\Collection\User $influencers
     * @return \Klout\Model\User
     */
    public function setInfluencers(UserCollection $influencers)
    {
        $this->influencers = $influencers;

        return $this;
    }

    /**
     * Get the Collection of User(s) who this user influences
     *
     * @return \Klout\Collection\User
     */
    public function getInfluencees()
    {
        return $this->influencees;
    }

    /**
     * Set the Collection of User(s) who this user influences
     *
     * @param  \Klout\Collection\User $influencees
     * @return \Klout\Model\User
     */
    public function setInfluencees(UserCollection $influencees)
    {
        $this->influencees = $influencees;

        return $this;
    }

    /**
     * Get the Topic(s) for this user
     *
     * @return \Klout\Collection\Topic
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Set the Collection of Topic(s) for the user
     *
     * @param  \Klout\Collection\Topic $topics
     * @return \Klout\Model\User
     */
    public function setTopics(TopicCollection $topics)
    {
        $this->topics = $topics;

        return $this;
    }

    /**
     * Populate the object with an array of data
     * Allows passing in the influence data array from the API
     * Allows passing in the topics data array from the API
     *
     * @param  array             $userData
     * @param  array             $influenceData (optional)
     * @param  array             $topicsData    (optional)
     * @return \Klout\Model\User
     */
    public function populate(array $userData, array $influenceData = null, array $topicsData = null)
    {

        if (empty($userData) && (!empty($influenceData) || !empty($topicsData))) {
            throw new InvalidArgumentException('Must have userData if you have influence or topic data.');
        } elseif (empty($userData)) {
            return $this;
        }

        if (empty($userData['kloutId'])) {
            throw new InvalidArgumentException('userData does not contain a kloutId.');
        }

        $this->setKloutId($userData['kloutId']);
        $this->setNickname($userData['nick']);

        $score = new Score();
        $scoreData = array();
        if (!empty($userData['score'])) {
            // Need to change the score data becauce the call to user.json
            // will return the 'score' as an array but the user.json/score call
            // will return the score data in a different format
            $scoreData = array(
                'score' => $userData['score']['score'],
                'bucket' => isset($userData['score']['bucket']) ? $userData['score']['bucket'] : null,
                'scoreDeltas' => $userData['scoreDeltas'],
            );
            $score->populate($userData['kloutId'], $scoreData);
        }
        $this->setScore($score);

        $influencers = new UserCollection();
        $influencees = new UserCollection();
        if (!empty($influenceData)) {
            if (isset($influenceData['myInfluencers']) && !empty($influenceData['myInfluencers'])) {
                $influencersData = array();
                foreach ($influenceData['myInfluencers'] as $value) {
                    if (empty($value['entity']) || empty($value['entity']['payload'])) {
                        continue;
                    }
                    $influencersData[] = array('userData' => $value['entity']['payload']);
                }
                $influencers = self::createUserCollection($influencersData);
            }

            if (isset($influenceData['myInfluencees']) && !empty($influenceData['myInfluencees'])) {
                $influenceesData = array();
                foreach ($influenceData['myInfluencees'] as $value) {
                    if (empty($value['entity']) || empty($value['entity']['payload'])) {
                        continue;
                    }
                    $influenceesData[] = array('userData' => $value['entity']['payload']);
                }
                $influencees = self::createUserCollection($influenceesData);
            }
        }

        $this->setInfluencers($influencers);
        $this->setInfluencees($influencees);

        if (!empty($topicsData)) {
            $this->setTopics(Topic::createTopicCollection($topicsData));
        }

        return $this;
    }

    /**
     * Create a Collection of User(s)
     *
     * @param  array                    $userArray
     * @throws InvalidArgumentException
     * @return \Klout\Collection\User
     */
    public static function createUserCollection(array $userArray)
    {
        $users = new UserCollection();
        if (empty($userArray)) {
            return $users;
        }

        foreach ($userArray as $userData) {
            if (!is_array($userData) || empty($userData['userData'])) {
                throw new InvalidArgumentException('Invalid user data.');
            }

            // Need to init the array even if empty to avoid errors later
            if (!isset($userData['influenceData'])) {
                $userData['influenceData'] = array();
            }
            if (!isset($userData['topicsData'])) {
                $userData['topicsData'] = array();
            }
            $user = new self($userData['userData'], $userData['influenceData'], $userData['topicsData']);
            if (!$user->getKloutId()) {
                throw new InvalidArgumentException('Invalid user data.');
            }
            $users[(string) $user->getKloutId()] = $user;
        }

        return $users;
    }
}
