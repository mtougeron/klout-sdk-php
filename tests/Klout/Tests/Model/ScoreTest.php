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
use Klout\Exception\LogicException;
use Klout\Model\Score;

class ScoreTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The kloutId for testing
     *
     * @var String
     */
    protected $kloutId = '615089';

    /**
     * The score data array for testing
     *
     * @var array
     */
    protected $scoreData = array (
        'score' => 52.715304066155,
        'scoreDelta' => array(
            'dayChange' => 0.46133213790044,
            'weekChange' => 0.43842620166831,
            'monthChange' => 0.7441690936211,
        ),
        'bucket' => '50-59',
    );

    /**
     * @covers \Klout\Model\Score::__construct
     */
    public function testNewEmptyScore()
    {
        $score = new Score();
        $this->assertNull($score->getKloutId());
        $this->assertNull($score->getScore());
        $this->assertNull($score->getBucket());
        $this->assertNull($score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::__construct
     */
    public function testNewScoreWithKloutIdButNotScoreData()
    {
        $score = new Score($this->kloutId);
        $this->assertNull($score->getKloutId());
        $this->assertNull($score->getScore());
        $this->assertNull($score->getBucket());
        $this->assertNull($score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::__construct
     */
    public function testNewScoreWithScoreDataButNotKloutId()
    {
        $score = new Score(null, $this->scoreData);
        $this->assertNull($score->getKloutId());
        $this->assertNull($score->getScore());
        $this->assertNull($score->getBucket());
        $this->assertNull($score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::__construct
     * @covers \Klout\Model\Score::populate
     */
    public function testNewScoreWithData()
    {
        $score = new Score($this->kloutId, $this->scoreData);
        $this->assertEquals($this->kloutId, $score->getKloutId());
        $this->assertEquals($this->scoreData['score'], $score->getScore());
        $this->assertEquals($this->scoreData['bucket'], $score->getBucket());
        $this->assertEquals($this->scoreData['scoreDelta'], $score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::setKloutId
     * @covers \Klout\Model\Score::getKloutId
     */
    public function testGetKloutId()
    {
        $score = new Score();
        $this->assertNull($score->getKloutId());
        $score->setKloutId($this->kloutId);
        $this->assertEquals($this->kloutId, $score->getKloutId());
    }

    /**
     * @covers \Klout\Model\Score::setScore
     * @covers \Klout\Model\Score::getScore
     */
    public function testGetScore()
    {
        $score = new Score();
        $this->assertNull($score->getScore());
        $score->setScore($this->scoreData['score']);
        $this->assertEquals($this->scoreData['score'], $score->getScore());
    }

    /**
     * @covers \Klout\Model\Score::setBucket
     * @covers \Klout\Model\Score::getBucket
     */
    public function testGetBucket()
    {
        $score = new Score();
        $this->assertNull($score->getBucket());
        $score->setBucket($this->scoreData['bucket']);
        $this->assertEquals($this->scoreData['bucket'], $score->getBucket());
    }

    /**
     * @covers \Klout\Model\Score::setDeltas
     * @covers \Klout\Model\Score::getDeltas
     */
    public function testGetDeltas()
    {
        $score = new Score();
        $this->assertNull($score->getDeltas());
        $score->setDeltas($this->scoreData['scoreDelta']);
        $this->assertEquals($this->scoreData['scoreDelta'], $score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::getDeltaByName
     */
    public function testGetDeltaByName()
    {
        $score = new Score();
        $this->assertNull($score->getDeltas());
        $score->setDeltas($this->scoreData['scoreDelta']);
        $this->assertEquals($this->scoreData['scoreDelta']['dayChange'], $score->getDeltaByName('dayChange'));
        $this->assertEquals($this->scoreData['scoreDelta']['weekChange'], $score->getDeltaByName('weekChange'));
        $this->assertEquals($this->scoreData['scoreDelta']['monthChange'], $score->getDeltaByName('monthChange'));
    }

    /**
     * @covers \Klout\Model\Score::getDeltaByName
     */
    public function testGetDeltaByNameNotSetException()
    {
        $this->setExpectedException('LogicException');
        $score = new Score();
        $score->getDeltaByName('dayChange');
    }

    /**
     * @covers \Klout\Model\Score::getDeltaByName
     */
    public function testGetDeltaByNameInvalidNameException()
    {
        $this->setExpectedException('InvalidArgumentException');
        $score = new Score();
        $score->setDeltas($this->scoreData['scoreDelta']);
        $score->getDeltaByName('foo');
    }

    /**
     * @covers \Klout\Model\Score::populate
     */
    public function testPopulateWithInvalidKloutId()
    {
        $this->setExpectedException('InvalidArgumentException');
        $score = new Score();
        $score->populate('', array());
    }

    /**
     * @covers \Klout\Model\Score::populate
     */
    public function testPopulateWithoutScoreData()
    {
        $score = new Score();
        $score->populate($this->kloutId, array());
        $this->assertEquals($this->kloutId, $score->getKloutId());
        $this->assertNull($score->getScore());
        $this->assertNull($score->getBucket());
        $this->assertNull($score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::populate
     */
    public function testPopulateWithScoreDelta()
    {
        $score = new Score();
        $score->populate($this->kloutId, $this->scoreData);
        $this->assertEquals($this->kloutId, $score->getKloutId());
        $this->assertEquals($this->scoreData['score'], $score->getScore());
        $this->assertEquals($this->scoreData['bucket'], $score->getBucket());
        $this->assertEquals($this->scoreData['scoreDelta'], $score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::populate
     */
    public function testPopulateWithScoreDeltaWithS()
    {
        $data = $this->scoreData;
        $data['scoreDeltas'] = $data['scoreDelta'];
        unset($data['scoreDelta']);

        $score = new Score();
        $score->populate($this->kloutId, $data);
        $this->assertEquals($this->kloutId, $score->getKloutId());
        $this->assertEquals($this->scoreData['score'], $score->getScore());
        $this->assertEquals($this->scoreData['bucket'], $score->getBucket());
        $this->assertEquals($this->scoreData['scoreDelta'], $score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::populate
     */
    public function testPopulateWithoutBucket()
    {
        $data = $this->scoreData;
        unset($data['bucket']);

        $score = new Score();
        $score->populate($this->kloutId, $data);
        $this->assertEquals($this->kloutId, $score->getKloutId());
        $this->assertEquals($this->scoreData['score'], $score->getScore());
        $this->assertNull($score->getBucket());
        $this->assertEquals($this->scoreData['scoreDelta'], $score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::populate
     */
    public function testPopulateWithoutScore()
    {
        $data = $this->scoreData;
        unset($data['score']);

        $score = new Score();
        $score->populate($this->kloutId, $data);
        $this->assertEquals($this->kloutId, $score->getKloutId());
        $this->assertNull($score->getScore());
        $this->assertEquals($this->scoreData['bucket'], $score->getBucket());
        $this->assertEquals($this->scoreData['scoreDelta'], $score->getDeltas());
    }

    /**
     * @covers \Klout\Model\Score::populate
     */
    public function testPopulateWithoutScoreDelta()
    {
        $data = $this->scoreData;
        unset($data['scoreDelta']);

        $score = new Score();
        $score->populate($this->kloutId, $data);
        $this->assertEquals($this->kloutId, $score->getKloutId());
        $this->assertEquals($this->scoreData['score'], $score->getScore());
        $this->assertEquals($this->scoreData['bucket'], $score->getBucket());
        $this->assertNull($score->getDeltas());
    }

}
