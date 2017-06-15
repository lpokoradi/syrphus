<?php

namespace TestForge\Syrphus\CustomFieldBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FieldTypeOption
 *
 * @ORM\Table(name="field_type_options")
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\CustomFieldBundle\Repository\FieldTypeOptionRepository")
 */
class FieldTypeOption
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var FieldType
     *
     * @ORM\ManyToOne(targetEntity="FieldType", inversedBy="options")
     * @ORM\JoinColumn(name="field_type_id", referencedColumnName="id")
     */
    private $fieldType;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="defaultValue", type="text", nullable=true)
     */
    private $defaultValue;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


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
     * Set custom field type
     *
     * @param CustomFieldType $cft
     *
     * @return FieldTypeOption
     */
    public function setCustomFieldType($cft)
    {
        $this->fieldType = $cft;

        return $this;
    }

    /**
     * Get custom field type
     *
     * @return FieldType
     */
    public function getCustomFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FieldTypeOption
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return FieldTypeOption
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set defaultValue
     *
     * @param string $defaultValue
     *
     * @return FieldTypeOption
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return FieldTypeOption
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
