<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\HookHandlers;

use Drupal\Core\Form\FormStateInterface;

interface FormAlterHandlerInterface {

  public function alter(array &$form, FormStateInterface $formState, string $formId): void;

}
