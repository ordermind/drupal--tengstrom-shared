<?php

declare(strict_types=1);

namespace Ordermind\DrupalTengstromShared\HookHandlers;

interface PreprocessHandlerInterface {

  public function preprocess(array &$variables): void;

}
