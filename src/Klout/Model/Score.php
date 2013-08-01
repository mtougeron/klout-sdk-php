<?php

namespace Klout\Model;

class Score extends AbstractModel
{
    /* @var string - The user's Klout ID */
    protected $kloutId;
    protected $score;
    protected $bucket;
    protected $deltas;

    public function __construct($kloutId = null, array $scoreData = null)
    {

        if (!empty($kloutId) && !empty($scoreData)) {
            $this->populate($kloutId, $scoreData);
        }
    }

    public function setKloutId($kloutId)
    {
        $this->kloutId = $kloutId;

        return $this;
    }

    public function getKloutId()
    {
        return $this->kloutId;
    }

    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    public function getScore()
    {
        return $this->score;
    }

    public function setBucket($bucket)
    {
        $this->bucket = $bucket;

        return $this;
    }

    public function setDeltas(array $deltas)
    {
        $this->deltas = $deltas;

        return $this;
    }

    public function getDeltas()
    {
        return $this->deltas;
    }

    public function getDeltaByName($deltaName)
    {
        if (empty($this->deltas)) {
            throw new Klout\Exception('Score Deltas for user not loaded.');
        }

        if (isset($this->deltas[$deltaName])) {
            return $this->deltas[$deltaName];
        } else {
            return null;
        }
    }

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
