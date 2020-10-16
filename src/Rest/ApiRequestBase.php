<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Rest;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\api_tools\Request\Request;
use Drupal\api_tools\Response\Response;
use Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for api_tools.api_request_manager plugins.
 */
abstract class ApiRequestBase extends PluginBase implements ContainerFactoryPluginInterface {

  /**
   * The api manager.
   *
   * @var \Drupal\api_tools\Rest\ApiManager
   */
  protected $apiManager;

  /**
   * Constructs a new instance.
   *
   * @param array $configuration
   *   The configuration.
   * @param string $plugin_id
   *   The plugin id.
   * @param array $plugin_definition
   *   The plugin definition.
   * @param \Drupal\api_tools\Rest\ApiManager $apiManager
   *   The api manager.
   */
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, ApiManager $apiManager) {
    $this->apiManager = $apiManager;

    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('api_tools.rest.api_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function request(Request $request, callable $callback) : Response {
    return $this->requestMultiple([$request], $callback)->current();
  }

  /**
   * {@inheritdoc}
   */
  public function requestMultiple(array $requests, callable $callback): Generator {
    $promises = [];

    foreach ($requests as $request) {
      $promises[] = $this->apiManager->createPromise($request);
    }

    yield from $this->apiManager->handlePromises($promises, $callback);
  }

}
