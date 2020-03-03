<?php

declare(strict_types = 1);

namespace Drupal\druidfi_api_tools\Rest;

/**
 * Factory to initialize new API request instances.
 */
final class RequestFactory {

  /**
   * The request plugin manager.
   *
   * @var \Drupal\druidfi_api_tools\Rest\ApiRequestPluginManager
   */
  private $requestManager;

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\druidfi_api_tools\Rest\ApiRequestPluginManager $apiRequestManager
   *   The api request manager.
   */
  public function __construct(ApiRequestPluginManager $apiRequestManager) {
    $this->requestManager = $apiRequestManager;
  }

  /**
   * Service factory callback to create new API request instances.
   *
   * @param string $id
   *   The plugin id.
   *
   * @return \Drupal\druidfi_api_tools\Rest\ApiRequestBase
   *   The manager instance.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function create(string $id) : ApiRequestBase {
    return $this->requestManager->createInstance($id);
  }

}

