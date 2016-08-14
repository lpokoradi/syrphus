<?php
/**
 * Created by PhpStorm.
 * User: laszl
 * Date: 8/3/2016
 * Time: 9:36 PM
 */

namespace TestForge\Syrphus\CustomFieldBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomField
 *
 * @ORM\Table(name="custom_field_values")
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\CustomFieldBundle\Repository\CustomFieldValueRepository")
 */

class CustomFieldValue
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var CustomField
     * @ORM\ManyToOne(targetEntity="TestForge\Syrphus\CustomFieldBundle\Entity\CustomField")
     * @ORM\JoinColumn(name="custom_field_id", referencedColumnName="id")
     */
    private $custom_field;

    /**
     * @var int
     * @ORM\Column(name="entity_id", type="integer")
     */
    private $entity_id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="valid_from", type="datetimetz", nullable=false)
     */
    private $validFrom;

    /**
     * @var \DateTime
     * @ORM\Column(name="valid_thru", type="datetimetz", nullable=true)
     */
    private $validThru;

    public function __construct()
    {
        $this->validFrom = new \DateTime("now");
        $this->validThru = null;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return CustomField
     */
    public function getCustomField()
    {
        return $this->custom_field;
    }

    /**
     * @param CustomField $custom_field
     * @return CustomFieldValue
     */
    public function setCustomField($custom_field)
    {
        $this->custom_field = $custom_field;
        return $this;
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * @param int $entity_id
     * @return CustomFieldValue
     */
    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @return \DateTime
     */
    public function getValidThru()
    {
        return $this->validThru;
    }

    /**
     * @param \DateTime $validThru
     * @return CustomFieldValue
     */
    public function setValidThru($validThru)
    {
        $this->validThru = $validThru;
        return $this;
    }
}