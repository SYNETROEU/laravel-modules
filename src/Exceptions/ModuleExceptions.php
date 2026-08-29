<?php

declare(strict_types=1);

namespace Synetro\LaravelModules\Exceptions;

class ModuleNotFoundException extends \RuntimeException {}
class ModuleDependencyException extends \RuntimeException {}
class CircularDependencyException extends \RuntimeException {}
class InvalidModuleMetadataException extends \InvalidArgumentException {}
class ModuleAlreadyEnabledException extends \RuntimeException {}
class ModuleAlreadyDisabledException extends \RuntimeException {}
