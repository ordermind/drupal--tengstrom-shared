<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\Tests\Fixtures\Factories;

use Drupal\Component\DependencyInjection\Container;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Cache\Context\CacheContextsManager;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Prophecy\Argument;
use Prophecy\Prophet;

class TestServiceContainerFactory {

  public function createWithBasicServices(): Container {
    $prophet = new Prophet();

    $mockCacheContextManager = $prophet->prophesize(CacheContextsManager::class);
    $mockCacheContextManager->assertValidTokens(Argument::cetera())->willReturn(TRUE);
    $cacheContextManager = $mockCacheContextManager->reveal();

    $mockCacheTagsInvalidator = $prophet->prophesize(CacheTagsInvalidatorInterface::class);
    $cacheTagsInvalidator = $mockCacheTagsInvalidator->reveal();

    $mockTranslationService = $prophet->prophesize(TranslationInterface::class);
    $translationService = $mockTranslationService->reveal();

    $mockModuleHandler = $prophet->prophesize(ModuleHandlerInterface::class);
    $moduleHandler = $mockModuleHandler->reveal();

    $mockLanguageManager = $prophet->prophesize(LanguageManagerInterface::class);
    $languageManager = $mockLanguageManager->reveal();

    $container = new Container();

    $container->set('cache_contexts_manager', $cacheContextManager);
    $container->set('cache_tags.invalidator', $cacheTagsInvalidator);
    $container->set('string_translation', $translationService);
    $container->set('module_handler', $moduleHandler);
    $container->set('language_manager', $languageManager);

    return $container;
  }

}
