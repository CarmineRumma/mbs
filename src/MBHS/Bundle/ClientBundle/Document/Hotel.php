<?php

namespace MBHS\Bundle\ClientBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Gedmo\Blameable\Traits\BlameableDocument;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableDocument;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use MBHS\Bundle\BaseBundle\Document\BaseDocument;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * Class Hotel
 * @author Aleksandr Arofikin <sashaaro@gmail.com>
 *
 * @ODM\Document()
 * @Gedmo\Loggable()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @MongoDBUnique(fields="internalID", message="The hotel with the same internalID already exists")
 */
class Hotel extends BaseDocument implements \JsonSerializable
{
    use TimestampableDocument;
    use SoftDeleteableDocument;
    use BlameableDocument;

    /**
     * @var string
     * @ODM\String()
     */
    protected $internalID;

    /**
     * @var Client
     * @ODM\ReferenceOne(targetDocument="Client")
     */
    protected $client;

    /**
     * @var string
     * @ODM\String()
     */
    protected $title;

    /**
     * @var string
     * @ODM\String()
     */
    protected $city;
    /**
     * @var ArrayCollection|Unwelcome[]
     * @ODM\ReferenceMany(targetDocument="Unwelcome", mappedBy="hotel")
     */
    protected $unwelcome;


    public function __construct()
    {
        $this->unwelcome = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getInternalID()
    {
        return $this->internalID;
    }

    /**
     * @param string $internalID
     * @return $this
     */
    public function setInternalID($internalID)
    {
        $this->internalID = $internalID;
        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return $this
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function addUnwelcome(Unwelcome $unwelcome)
    {
        $this->unwelcome->add($unwelcome);
        return $this;
    }

    public function getUnwelcome()
    {
        return $this->unwelcome;
    }

    public function setUnwelcome($unwelcome)
    {
        $this->unwelcome = $unwelcome;
        return $this;
    }

    /**
     * @param \MBHS\Bundle\ClientBundle\Document\Unwelcome $unwelcome
     */
    public function removeUnwelcome(\MBHS\Bundle\ClientBundle\Document\Unwelcome $unwelcome)
    {
        $this->unwelcome->removeElement($unwelcome);
    }

    public function jsonSerialize()
    {
        return [
            'title' => $this->title,
            'city' => $this->city,
        ];
    }

    public function getUnwelcomeCount()
    {
        return count($this->unwelcome);
    }
}
