<?php

namespace TestForge\Syrphus\CustomFieldBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomField
 *
 * @ORM\Table(name="custom_fields")
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\CustomFieldBundle\Repository\CustomFieldRepository")
 */
class CustomField
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="default_label", type="string", length=128)
     */
    private $defaultLabel;

    /**
     * @var FieldType
     *
     * @ORM\ManyToOne(targetEntity="FieldType")
     * @ORM\JoinColumn(name="field_type_id", referencedColumnName="id")
     */
    private $fieldType;

    /**
     * @var array
     *
     * @ORM\Column(name="options_json", type="json_array", nullable=true)
     */
    private $options;

    /**
     * @var array
     *
     * @ORM\Column(name="constrain_json", type="json_array", nullable=true)
     */
    private $constrains;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=128)
     */
    private $entity;

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
     * @return CustomField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get defaultLabel
     *
     * @return string
     */
    public function getDefaultLabel()
    {
        return $this->defaultLabel;
    }

    /**
     * Set defaultLabel
     *
     * @param string $defaultLabel
     *
     * @return CustomField
     */
    public function setDefaultLabel($defaultLabel)
    {
        $this->defaultLabel = $defaultLabel;

        return $this;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options
     *
     * @param array $options
     *
     * @return CustomField
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get constrainJson
     *
     * @return array
     */
    public function getConstrains()
    {
        return $this->constrains;
    }

    /**
     * Set constrains
     *
     * @param array $constrains
     *
     * @return CustomField
     */
    public function setConstrains($constrains)
    {
        $this->constrains = $constrains;

        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set fieldType
     *
     * @param \TestForge\Syrphus\CustomFieldBundle\Entity\FieldType $fieldType
     *
     * @return CustomField
     */
    public function setFieldType(\TestForge\Syrphus\CustomFieldBundle\Entity\FieldType $fieldType = null)
    {
        $this->fieldType = $fieldType;

        return $this;
    }
}
