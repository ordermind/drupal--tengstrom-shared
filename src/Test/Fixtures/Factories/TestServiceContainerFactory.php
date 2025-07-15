<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\Test\Fixtures\Factories;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Component\DependencyInjection\Container;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Cache\Context\CacheContextsManager;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Language\LanguageDefault;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\StringTranslation\TranslationManager;
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

    $translationService = new TranslationManager(new LanguageDefault(['id' => 'x-default']));

    $mockModuleHandler = $prophet->prophesize(ModuleHandlerInterface::class);
    $moduleHandler = $mockModuleHandler->reveal();

    $mockLanguageManager = $prophet->prophesize(LanguageManagerInterface::class);
    $languageManager = $mockLanguageManager->reveal();

    $expectedTime = 1690208874;
    $mockTimeService = $prophet->prophesize(TimeInterface::class);
    $mockTimeService->getRequestTime()->willReturn($expectedTime);
    $timeService = $mockTimeService->reveal();

    $container = new Container();

    $container->set('cache_contexts_manager', $cacheContextManager);
    $container->set('cache_tags.invalidator', $cacheTagsInvalidator);
    $container->set('string_translation', $translationService);
    $container->set('module_handler', $moduleHandler);
    $container->set('language_manager', $languageManager);
    $container->set('time', $timeService);
    $container->set('datetime.time', $timeService);
    $container->set(TimeInterface::class, $timeService);

    return $container;
  }

}
