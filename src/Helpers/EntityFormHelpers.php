<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\Helpers;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;

class EntityFormHelpers {

  public static function isContentEntityForm(FormInterface $objForm): bool {
    return $objForm instanceof ContentEntityForm;
  }

  public static function getEntityForm(FormStateInterface $formState): ContentEntityForm {
    $objEntityForm = $formState->getFormObject();

    if (!static::isContentEntityForm($objEntityForm)) {
      throw new \InvalidArgumentException('This is not a content entity form.');
    }

    return $objEntityForm;
  }

  public static function getContentEntityAction(FormStateInterface $formState): string {
    $objEntityForm = static::getEntityForm($formState);

    if ($objEntityForm instanceof ContentEntityDeleteForm) {
      return 'delete';
    }

    $entity = $objEntityForm->getEntity();

    if ($entity->isNew()) {
      return 'create';
    }

    return 'update';
  }

}
