<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\Test\Fixtures\EntityStorage;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\MemoryCache\MemoryCache;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Ordermind\Helpers\ValueObject\Integer\PositiveInteger;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * An entity storage that saves the entities to a PHP array,
 * used for testing purposes.
 */
class ContentEntityArrayStorage extends EntityStorageBase {
  /**
   * @property EntityInterface[]
   */
  protected array $items = [];

  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type): static {
    $timeService = \Drupal::service(TimeInterface::class);

    return new static($entity_type, new MemoryCache($timeService));
  }

  /**
   * {@inheritDoc}
   *
   * @param int $id
   */
  protected function has($id, EntityInterface $entity): bool {
    return $this->hasEntityId((int) $id);
  }

  /**
   * {@inheritDoc}
   */
  protected function doDelete($entities) {
    foreach ($entities as $entity) {
      unset($this->items[$entity->id()]);
    }
  }

  /**
   * {@inheritDoc}
   *
   * @param int $id
   */
  protected function doSave($id, EntityInterface $entity): bool|int {
    $this->items[$id] = $entity;

    return SAVED_NEW;
  }

  /**
   * {@inheritDoc}
   */
  protected function getQueryServiceName(): string {
    return '';
  }

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
   */
  public function create(array $values = []): EntityInterface {
    if (!isset($values['id'])) {
      $values['id'] = $this->getNextId();
    }

    // Use value object for validation.
    new PositiveInteger($values['id']);

    return parent::create($values);
  }

  protected function getNextId(): int {
    $itemCount = $this->count();

    if ($itemCount) {
      return $itemCount + 1;
    }

    return 1;
  }

  /**
   * {@inheritDoc}
   *
   * @param int[]|null $ids
   */
  protected function doLoadMultiple(?array $ids = NULL): array {
    if (!$ids) {
      return [];
    }

    return array_intersect_key($this->items, array_flip($ids));
  }

  /**
   * Checks if an entity id exists in the storage.
   */
  public function hasEntityId(int $entityId): bool {
    return isset($this->items[$entityId]);
  }

  public function count(): int {
    return count($this->items);
  }

  /**
   * Load all persisted entities.
   */
  public function loadAll(): array {
    return $this->items;
  }

}

namespace Drupal\Core\Config\Entity;

if (!defined('SAVED_NEW')) {
  define('SAVED_NEW', 1);
}
if (!defined('SAVED_UPDATED')) {
  define('SAVED_UPDATED', 2);
}
