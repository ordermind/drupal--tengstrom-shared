<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\Test\Fixtures\EntityStorage;

use Drupal\Core\Cache\MemoryCache\MemoryCache;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An entity storage that saves the entities to a PHP array, used for testing purposes.
 *
 * @template T of EntityInterface
 */
class EntityArrayStorage extends EntityStorageBase {
  /**
   * @property object[]
   *
   * Initialize the zero element with null to simulate that the id counter
   * starts with 1 for new items.
   */
  protected array $items = [0 => NULL];

  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static($entity_type, new MemoryCache());
  }

  /**
   * {@inheritDoc}
   *
   * @param T $entity
   */
  protected function has($id, EntityInterface $entity) {
    return isset($this->items[$id]);
  }

  /**
   * {@inheritDoc}
   *
   * @param T[] $entities
   */
  protected function doDelete($entities) {
    foreach ($entities as $entity) {
      unset($this->items[$entity->id()]);
    }
  }

  /**
   * {@inheritDoc}
   *
   * @param T $entity
   */
  protected function doSave($id, EntityInterface $entity) {
    $this->items[$id] = $entity;
  }

  /**
   * {@inheritDoc}
   */
  protected function getQueryServiceName() {}

  /**
   * {@inheritDoc}
   */
  public function loadRevision($revision_id) {}

  /**
   * {@inheritDoc}
   */
  public function deleteRevision($revision_id) {}

  /**
   * {@inheritDoc}
   *
   * @return T
   */
  public function create(array $values = []) {
    if (!isset($values['id'])) {
      $values['id'] = $this->count() + 1;
    }

    return parent::create($values);
  }

  /**
   * {@inheritDoc}
   *
   * return T[]
   */
  protected function doLoadMultiple(?array $ids = NULL): array {
    if (!$ids) {
      return [];
    }

    return array_intersect_key($this->items, array_flip($ids));
  }

  /**
   * Subtract by one to ignore the zero element.
   */
  public function count(): int {
    return count($this->getAllItemsExceptDummyItem());
  }

  /**
   * Load all persisted entities.
   *
   * @return T[]
   */
  public function loadAll(): array {
    return $this->getAllItemsExceptDummyItem();
  }

  /**
   * @return T[] All items except the first dummy item. Please note that
   * the items are also reordered.
   */
  protected function getAllItemsExceptDummyItem(): array {
    return array_slice(array_values($this->items), 1);
  }

}

namespace Drupal\Core\Config\Entity;

if (!defined('SAVED_NEW')) {
  define('SAVED_NEW', 1);
}
if (!defined('SAVED_UPDATED')) {
  define('SAVED_UPDATED', 2);
}
