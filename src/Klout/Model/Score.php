<?php

namespace Klout\Model;

use Klout\Exception;
use Klout\Exception\InvalidArgumentException;
use Klout\Model\AbstractModel;

class Score extends AbstractModel
{
    /**
     * The user's Klout Id
     *
     * @var String
     */
    protected $kloutId;

    /**
     * The Klout score
     *
     * @var Float
     */
    protected $score;

    /**
     * The bucket the score is in
     *
     * @var String
     */
    protected $bucket;

    /**
     * The array of delta scores
     *
     * @var array
     */
    protected $deltas;

    /**
     * The constructor
     *
     * @param String $kloutId
     * @param array  $scoreData
     */
    public function __construct($kloutId = null, array $scoreData = null)
    {
        if (!empty($kloutId) && !empty($scoreData)) {
            $this->populate($kloutId, $scoreData);
        }
    }

    /**
     * Set the Klout Id
     *
     * @param  String             $kloutId
     * @return \Klout\Model\Score
     */
    public function setKloutId($kloutId)
    {
        $this->kloutId = $kloutId;

        return $this;
    }

    /**
     * Get the Klout Id
     *
     * @return string
     */
    public function getKloutId()
    {
        return $this->kloutId;
    }

    /**
     * Set the Score value
     *
     * @param  Float              $score
     * @return \Klout\Model\Score
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get the Score value
     *
     * @return Float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set the bucket the score is part of
     *
     * @param  String             $bucket
     * @return \Klout\Model\Score
     */
    public function setBucket($bucket)
    {
        $this->bucket = $bucket;

        return $this;
    }

    /**
     * Set the array of delta values
     *
     * @param  array              $deltas
     * @return \Klout\Model\Score
     */
    public function setDeltas(array $deltas)
    {
        $this->deltas = $deltas;

        return $this;
    }

    /**
     * Get the array of score deltas
     *
     * @return array
     */
    public function getDeltas()
    {
        return $this->deltas;
    }

    /**
     * Get a specific score delta
     *
     * @param  String                   $deltaName
     * @throws Exception
     * @throws InvalidArgumentException
     * @return Float
     */
    public function getDeltaByName($deltaName)
    {
        if (empty($this->deltas)) {
            throw new Exception('Score Deltas for user not loaded.');
        }

        if (!isset($this->deltas[$deltaName])) {
            throw new InvalidArgumentException('Score Deltas for user not loaded.');
        }

        return $this->deltas[$deltaName];
    }

    /**
     * Populate the object based on an array of data
     *
     * @param  String             $kloutId
     * @param  array              $scoreData
     * @return \Klout\Model\Score
     */
    public function populate($kloutId, array $scoreData)
    {
        if ((empty($kloutId) && empty($this->kloutId)) || empty($scoreData)) {
            return $this;
        }

        $this->setKloutId($kloutId);
        $this->setBucket($scoreData['bucket']);
        $this->setScore($scoreData['score']);

        // Have to conditionalize this. Sometimes it is called
        // scoreDelta and sometimes scoreDeltas
        if (isset($scoreData['scoreDeltas'])) {
            $this->setDeltas($scoreData['scoreDeltas']);
        } elseif (isset($scoreData['scoreDelta'])) {
            $this->setDeltas($scoreData['scoreDelta']);
        }

        return $this;
    }
}
