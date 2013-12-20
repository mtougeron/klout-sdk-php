<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Model;

use Klout\Collection\Topic as TopicCollection;
use Klout\Exception\InvalidArgumentException;
use Klout\Model\AbstractModel;

class Topic extends AbstractModel
{

    /**
     * The topic's Id
     *
     * @var String
     */
    protected $topicId;

    /**
     * The topic's display name
     *
     * @var String
     */
    protected $displayName;

    /**
     * The topic's name
     *
     * @var String
     */
    protected $name;

    /**
     * The topic's url slug
     *
     * @var String
     */
    protected $slug;

    /**
     * The image url for the topic's icon
     *
     * @var String
     */
    protected $imageUrl;

    /**
     * The topic type
     *
     * @var String
     */
    protected $topicType;

    /**
     * The constructor
     *
     * @param array $topicData
     */
    public function __construct(array $topicData = array())
    {
        if (!empty($topicData)) {
            $this->populate($topicData);
        }
    }

    /**
     * Get the topic's Id
     *
     * @return string
     */
    public function getTopicId()
    {
        return $this->topicId;
    }

    /**
     * Set the topic's Id
     *
     * @param  String             $topicId
     * @return \Klout\Model\Topic
     */
    public function setTopicId($topicId)
    {
        $this->topicId = $topicId;

        return $this;
    }

    /**
     * Get the topic's display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set the topic's display name
     *
     * @param  String             $displayName
     * @return \Klout\Model\Topic
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get the topic's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the topic
     *
     * @param  String             $name
     * @return \Klout\Model\Topic
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the topic's slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the topic's url slug
     *
     * @param  String             $slug
     * @return \Klout\Model\Topic
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the topic's image url for the icon
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * Set the topic's image url
     *
     * @param  String             $imageUrl
     * @return \Klout\Model\Topic
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get the topic type
     *
     * @return string
     */
    public function getTopicType()
    {
        return $this->topicType;
    }

    /**
     * Set the type of the topic
     *
     * @param  String             $topicType
     * @return \Klout\Model\Topic
     */
    public function setTopicType($topicType)
    {
        $this->topicType = $topicType;

        return $this;
    }

    /**
     * Populate the model based on an array of data
     *
     * @param  array              $topicData
     * @return \Klout\Model\Topic
     */
    public function populate(array $topicData)
    {
        if (empty($topicData)) {
            return $this;
        }

        $this->setTopicId($topicData['id']);
        $this->setdisplayName($topicData['displayName']);
        $this->setName($topicData['name']);
        $this->setSlug($topicData['slug']);
        $this->setImageUrl($topicData['imageUrl']);
        $this->setTopicType($topicData['topicType']);

        return $this;
    }

    /**
     * Create a Topic Collection prepopulated with an array of data
     *
     * @param  array                    $topicArray
     * @throws InvalidArgumentException
     * @return \Klout\Collection\Topic
     */
    public static function createTopicCollection(array $topicArray)
    {
        $topics = new TopicCollection();
        if (empty($topicArray)) {
            return $topics;
        }

        foreach ($topicArray as $topicData) {
            $topic = new self($topicData);
            if (!$topic->getTopicId()) {
                throw new InvalidArgumentException('Invalid topic data.');
            }
            $topics[(string) $topic->getTopicId()] = $topic;
        }

        return $topics;
    }

}
