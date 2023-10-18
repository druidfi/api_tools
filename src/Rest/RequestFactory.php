<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Rest;

/**
 * Factory to initialize new API request instances.
 */
final class RequestFactory {

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\api_tools\Rest\ApiRequestPluginManager $requestManager
   *   The api request manager.
   */
  public function __construct(private ApiRequestPluginManager $requestManager) {
  }

  /**
   * Service factory callback to create new API request instances.
   *
   * @param string $id
   *   The plugin id.
   *
   * @return \Drupal\api_tools\Rest\ApiRequestBase
   *   The manager instance.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginException
   */
  public function create(string $id) : ApiRequestBase {
    return $this->requestManager->createInstance($id);
  }

}
