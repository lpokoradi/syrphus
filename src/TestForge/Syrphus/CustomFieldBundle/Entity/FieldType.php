<?php

namespace TestForge\Syrphus\CustomFieldBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * FieldType
 *
 * @ORM\Table(name="field_types")
 * @ORM\Entity(repositoryClass="TestForge\Syrphus\CustomFieldBundle\Repository\FieldTypeRepository")
 */
class FieldType
{
    /**
     * @var string
     *
     * @ORM\Column(name="type_class", type="string", length=32, nullable=false)
     */
    protected $typeClass;
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, unique=true)
     */
    protected $name;
    /**
     * @var string
     *
     * @ORM\Column(name="class", type="text")
     */
    protected $class;
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="FieldTypeOption", mappedBy="fieldType")
     */
    private $options;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return FieldType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set class
     *
     * @param string $class
     *
     * @return FieldType
     */
    public function setClass($class)
    {
        $this->class = $class;
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
     * @return FieldType
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getTypeClass()
    {
        return $this->typeClass;
    }

    /**
     * @param string $typeClass
     * @return FieldType
     */
    public function setTypeClass($typeClass)
    {
        $this->typeClass = $typeClass;
        return $this;
    }

    /**
     * Add option
     *
     * @param \TestForge\Syrphus\CustomFieldBundle\Entity\FieldType $option
     *
     * @return FieldType
     */
    public function addOption(\TestForge\Syrphus\CustomFieldBundle\Entity\FieldType $option)
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * Remove option
     *
     * @param \TestForge\Syrphus\CustomFieldBundle\Entity\FieldType $option
     */
    public function removeOption(\TestForge\Syrphus\CustomFieldBundle\Entity\FieldType $option)
    {
        $this->options->removeElement($option);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }
}
