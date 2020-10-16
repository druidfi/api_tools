<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Rest;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * API request plugin manager.
 */
class ApiRequestPluginManager extends DefaultPluginManager {

  /**
   * Constructs a new instance.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/RestApiRequest',
      $namespaces,
      $module_handler,
      'Drupal\api_tools\Rest\ApiRequestBase',
      'Drupal\api_tools\Annotation\RestApiRequest'
    );
    $this->alterInfo('api_tools.rest.api_request_manager_info');
    $this->setCacheBackend($cache_backend, 'api_tools.rest.api_request_manager_plugins');
  }

}
