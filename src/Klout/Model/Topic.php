<?php

namespace Klout\Model;

use Klout\Exception\InvalidArgumentException;
use Klout\Collection\Topic as TopicCollection;

class Topic extends AbstractModel
{

    protected $topicId;
    protected $displayName;
    protected $name;
    protected $slug;
    protected $imageUrl;
    protected $topicType;

    public function __construct(array $topicData = null)
    {

        $this->populate($topicData);
    }

    public function getTopicId()
    {
        return $this->topicId;
    }

    public function setTopicId($topicId)
    {
        $this->topicId = $topicId;

        return $this;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }

    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getTopicType()
    {
        return $this->topicType;
    }

    public function setTopicType($topicType)
    {
        $this->topicType = $topicType;

        return $this;
    }

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
            $topics[$topic->getTopicId()] = $topic;
        }

        return $topics;
    }

}
