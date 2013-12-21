<?php
/*
 * @package    klout-sdk-php
 * @author     Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @copyright  Copyright (c) 2013 Mike Tougeron <github+klout-sdk-php@tougeron.com>
 * @license    http://opensource.org/licenses/MIT
 * @link       https://github.com/mtougeron/klout-sdk-php
 */

namespace Klout\Tests\Model;

use Klout\Exception\InvalidArgumentException;
use Klout\Model\Topic;

class TopicTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The topic data array for testing
     *
     * @var array
     */
    protected $topicData = array(
        array(
            'id' => '8226386291923137474',
            'displayName' => 'Software Development',
            'name' => 'Software Development',
            'slug' => 'software-development',
            'imageUrl' => 'http://kcdn3.klout.com/static/images/icons/generic-topic.png',
            'topicType' => 'sub',
        ),
        array(
            'id' => '7188611684654363095',
            'displayName' => 'MySQL',
            'name' => 'MySQL',
            'slug' => 'mysql',
            'imageUrl' => 'http://kcdn3.klout.com/static/images/icons/generic-topic.png',
            'topicType' => 'entity',
        ),
        array(
            'id' => '5144818194631006088',
            'displayName' => 'Software',
            'name' => 'Software',
            'slug' => 'software',
            'imageUrl' => 'http://kcdn3.klout.com/static/images/icons/generic-topic.png',
            'topicType' => 'sub',
        ),
    );

    /**
     * @covers \Klout\Model\Topic::__construct
    */
    public function testNewEmptyTopic()
    {
        $topic = new Topic();
        $this->assertNull($topic->getTopicId());
        $this->assertNull($topic->getDisplayName());
        $this->assertNull($topic->getName());
        $this->assertNull($topic->getSlug());
        $this->assertNull($topic->getImageUrl());
        $this->assertNull($topic->getTopicType());
    }

    /**
     * @covers \Klout\Model\Topic::__construct
     * @covers \Klout\Model\Topic::populate
     */
    public function testNewTopicWithData()
    {
        $topic = new Topic($this->topicData[0]);
        $this->assertEquals($this->topicData[0]['id'], $topic->getTopicId());
        $this->assertEquals($this->topicData[0]['displayName'], $topic->getDisplayName());
        $this->assertEquals($this->topicData[0]['name'], $topic->getName());
        $this->assertEquals($this->topicData[0]['slug'], $topic->getSlug());
        $this->assertEquals($this->topicData[0]['imageUrl'], $topic->getImageUrl());
        $this->assertEquals($this->topicData[0]['topicType'], $topic->getTopicType());
    }

    /**
     * @covers \Klout\Model\Topic::populate
     */
    public function testPopulateWithEmptyArray()
    {
        $topic = new Topic();
        $topic->populate(array());
        $this->assertNull($topic->getTopicId());
        $this->assertNull($topic->getDisplayName());
        $this->assertNull($topic->getName());
        $this->assertNull($topic->getSlug());
        $this->assertNull($topic->getImageUrl());
        $this->assertNull($topic->getTopicType());
    }

    /**
     * @covers \Klout\Model\Topic::setTopicId
     * @covers \Klout\Model\Topic::getTopicId
     */
    public function testGetTopicId()
    {
        $topic = new Topic();
        $this->assertNull($topic->getTopicId());
        $topic->setTopicId($this->topicData[0]['id']);
        $this->assertEquals($this->topicData[0]['id'], $topic->getTopicId());
    }

    /**
     * @covers \Klout\Model\Topic::setDisplayName
     * @covers \Klout\Model\Topic::getDisplayName
     */
    public function testGetDisplayName()
    {
        $topic = new Topic();
        $this->assertNull($topic->getDisplayName());
        $topic->setDisplayName($this->topicData[0]['displayName']);
        $this->assertEquals($this->topicData[0]['displayName'], $topic->getDisplayName());
    }

    /**
     * @covers \Klout\Model\Topic::setName
     * @covers \Klout\Model\Topic::getName
     */
    public function testGetName()
    {
        $topic = new Topic();
        $this->assertNull($topic->getName());
        $topic->setName($this->topicData[0]['name']);
        $this->assertEquals($this->topicData[0]['name'], $topic->getName());
    }

    /**
     * @covers \Klout\Model\Topic::setSlug
     * @covers \Klout\Model\Topic::getSlug
     */
    public function testGetSlug()
    {
        $topic = new Topic();
        $this->assertNull($topic->getSlug());
        $topic->setSlug($this->topicData[0]['slug']);
        $this->assertEquals($this->topicData[0]['slug'], $topic->getSlug());
    }

    /**
     * @covers \Klout\Model\Topic::setImageUrl
     * @covers \Klout\Model\Topic::getImageUrl
     */
    public function testGetImageUrl()
    {
        $topic = new Topic();
        $this->assertNull($topic->getImageUrl());
        $topic->setImageUrl($this->topicData[0]['imageUrl']);
        $this->assertEquals($this->topicData[0]['imageUrl'], $topic->getImageUrl());
    }

    /**
     * @covers \Klout\Model\Topic::setTopicType
     * @covers \Klout\Model\Topic::getTopicType
     */
    public function testGetTopicType()
    {
        $topic = new Topic();
        $this->assertNull($topic->getTopicType());
        $topic->setTopicType($this->topicData[0]['topicType']);
        $this->assertEquals($this->topicData[0]['topicType'], $topic->getTopicType());
    }

    /**
     * @covers \Klout\Model\Topic::createTopicCollection
     */
    public function testCreateTopicCollection()
    {
        $collection = Topic::createTopicCollection($this->topicData);
        $this->assertInstanceOf('Klout\Collection\Topic', $collection);
        $this->assertCount(3, $collection);
    }

    /**
     * @covers \Klout\Model\Topic::createTopicCollection
     */
    public function testCreateTopicCollectionEmptyArray()
    {
        $collection = Topic::createTopicCollection(array());
        $this->assertInstanceOf('Klout\Collection\Topic', $collection);
        $this->assertCount(0, $collection);
    }

    /**
     * @covers \Klout\Model\Topic::createTopicCollection
     */
    public function testCreateTopicCollectionWithInvalidData()
    {
        $this->setExpectedException('InvalidArgumentException');
        $data = $this->topicData;
        unset($data[1]['id']);

        $collection = Topic::createTopicCollection($data);
        $this->assertInstanceOf('Klout\Collection\Topic', $collection);
        $this->assertCount(3, $collection);
    }
}
