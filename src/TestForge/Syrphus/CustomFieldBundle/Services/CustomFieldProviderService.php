<?php
/**
 * Created by PhpStorm.
 * User: lpokoradi
 * Date: 11.06.16
 * Time: 00:05
 */

namespace TestForge\Syrphus\CustomFieldBundle\Services;

use DateInterval;
use Doctrine\Common\Cache\SQLite3Cache;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\Validator\Mapping\Cache\DoctrineCache;
use TestForge\Syrphus\CustomFieldBundle\Entity\Entity;

class CustomFieldProviderService
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @var DoctrineCache
     */
    protected $cache;

    public function __construct($em)
    {
        $this->manager = $em;

        $sqlite3 = new SQLite3(__DIR__ . '/../../../../../var/cache/data.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);
        $doctrineCache = new SQLite3Cache($sqlite3, 'cache');
        $this->cache = new DoctrineCache($doctrineCache);
    }

    public function createForm($class, $formBuilder, $id = null)
    {
        if ($class === null || $formBuilder === null) {
            throw new Exception('CustomFormBuilderService: I don\'t know which entity or formBuilder!');
        }

        $entityItem = $this->cache->getItem($class);
        $values = null;

        if (null !== $id)
        {
            $values = $this->getCustomFieldValuesFor($class, $id);
        }

        if (!$entityItem->isHit()) {
            foreach ($this->getCustomFields() as $field) {
                $options = array_merge($field->getOptions(), $values[$field->getName()]);
                $formBuilder = $formBuilder->add($field->getName(), $field->getFieldType()->getClass(),
                    $options);
            }

            $entityItem->set($formBuilder);
            $entityItem->expiresAfter(DateInterval::createFromDateString('12 hour'));
            $this->cache->save($entityItem);
        }

        return $entityItem->get();
    }

    public function getCustomFieldsFor($entity)
    {
        if (null === $entity) {
            throw new Exception('CustomFormBuilderService: I don\'t know which entity!');
        }
        return $this->manager->getRepository('TestForgeSyrphusCustomFieldBundle:CustomField')->findAllEntity($entity);
    }

    public function getCustomFieldValuesFor($class, $id)
    {
        if (null === $class) {
            throw new Exception('CustomFormBuilderService: Class is unknown.');
        }
        $entity = $this->manager->getRepository('TestForgeSyrphusCustomFieldBundle:Entity')->resolve($class);

        if (null === $entity) {
            throw new Exception('CustomFormBuilderService: I don\'t know which entity!');
        }

        return $this->manager->getRepository('TestForgeSyrphusCustomFieldBundle:CustomFieldValue')->findAllEntity($entity, $id);
    }

    public function registerEntity($class, $name)
    {
        $e = new Entity();
        $e->setName($name);
        $e->setClass($class);

        $this->manager->persist($e);
        $this->manager->flush();
    }
}