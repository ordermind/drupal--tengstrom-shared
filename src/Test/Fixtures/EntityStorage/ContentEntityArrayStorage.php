<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\Test\Fixtures\EntityStorage;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\ContentEntityStorageBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * A content entity storage that saves the entities to a PHP array, used for testing purposes.
 */
class ContentEntityArrayStorage extends ContentEntityStorageBase {
  /**
   * @property object[]
   *
   * Initialize the zero element with null to simulate that the id counter starts with 1 for new items.
   */
  protected array $items = [0 => NULL];

  /**
   * {@inheritDoc}
   */
  protected function initFieldValues(ContentEntityInterface $entity, array $values = [], array $field_names = []) {

  }

  /**
   * {@inheritDoc}
   */
  protected function readFieldItemsToPurge(FieldDefinitionInterface $field_definition, $batch_size) {}

  /**
   * {@inheritDoc}
   */
  protected function purgeFieldItems(ContentEntityInterface $entity, FieldDefinitionInterface $field_definition) {}

  /**
   * {@inheritDoc}
   */
  protected function doLoadMultipleRevisionsFieldItems($revision_ids) {}

  /**
   * {@inheritDoc}
   */
  protected function doSaveFieldItems(ContentEntityInterface $entity, array $names = []) {
    if ($entity->isNew()) {
      $this->items[] = $entity;
    }
    else {
      $this->items[$entity->id()] = $entity;
    }
  }

  /**
   * {@inheritDoc}
   */
  protected function doDeleteFieldItems($entities) {}

  /**
   * {@inheritDoc}
   */
  protected function doDeleteRevisionFieldItems(ContentEntityInterface $revision) {}

  /**
   * {@inheritDoc}
   */
  protected function doLoadMultiple(?array $ids = NULL): array {
    if (!$ids) {
      return [];
    }

    return array_intersect_key($this->items, array_flip($ids));
  }

  /**
   * {@inheritDoc}
   */
  protected function has($id, EntityInterface $entity) {}

  /**
   * {@inheritDoc}
   */
  protected function getQueryServiceName() {}

  /**
   * {@inheritDoc}
   */
  public function countFieldData($storage_definition, $as_bool = FALSE) {}

  /**
   * Subtract by one to ignore the zero element.
   */
  public function count(): int {
    return count($this->items) - 1;
  }

}

namespace Drupal\Core\Config\Entity;

if (!defined('SAVED_NEW')) {
  define('SAVED_NEW', 1);
}
if (!defined('SAVED_UPDATED')) {
  define('SAVED_UPDATED', 2);
}
