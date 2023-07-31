<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\Tests\Concerns;

trait AddsServicesToContainerDuringTest {

  protected function addSingleService(string $id, object $service): void {
    $this->addMultipleServices([$id => $service]);
  }

  protected function addMultipleServices(array $serviceDefinitions): void {
    $container = \Drupal::getContainer();

    foreach ($serviceDefinitions as $id => $service) {
      $container->set($id, $service);
    }

    \Drupal::setContainer($container);
  }

}
