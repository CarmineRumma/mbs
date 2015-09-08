<?php

namespace MBHS\Bundle\ClientBundle\Document;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Blameable\Traits\BlameableDocument;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableDocument;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use MBHS\Bundle\BaseBundle\Document\BaseDocument as Base;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ODM\Document(collection="UnwelcomeHistory", repositoryClass="MBHS\Bundle\ClientBundle\Document\UnwelcomeHistoryRepository")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class UnwelcomeHistory extends Base implements \JsonSerializable
{
    /**
     * Hook timestampable behavior
     * up\DateTimes createdAt, up\DateTimedAt fields
     */
    use TimestampableDocument;

    /**
     * Hook softdeleteable behavior
     * deletedAt field
     */
    use SoftDeleteableDocument;

    /**
     * Hook blameable behavior
     * createdBy&up\DateTimedBy fields
     */
    use BlameableDocument;

    /**
     * @var Tourist
     * @ODM\EmbedOne(targetDocument="MBHS\Bundle\ClientBundle\Document\Tourist")
     */
    protected $tourist;

    /**
     * @var Unwelcome[]
     * @ODM\EmbedMany(targetDocument="MBHS\Bundle\ClientBundle\Document\Unwelcome")
     */
    protected $items = [];

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @return Tourist|null
     */
    public function getTourist()
    {
        return $this->tourist;
    }

    /**
     * @param Tourist|null $tourist
     * @return $this
     */
    public function setTourist(Tourist $tourist = null)
    {
        $this->tourist = $tourist;
        return $this;
    }

    /**
     * @return ArrayCollection|Unwelcome[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Unwelcome $unwelcome
     * @return $this
     */
    public function removeItem(Unwelcome $unwelcome)
    {
        $this->items->removeElement($unwelcome);
        return $this;
    }

    /**
     * @param Unwelcome[] $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param Unwelcome $item
     * @return $this
     */
    public function addItem(Unwelcome $item)
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            //'id' => $this->id,
            'tourist' => $this->tourist,
            'items' => iterator_to_array($this->getItems())
        ];
    }
}