<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Rest;

use Drupal\api_tools\Request\Request;
use Drupal\api_tools\Response\Response;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for rest request plugins.
 */
abstract class ApiRequestBase extends PluginBase implements ContainerFactoryPluginInterface {

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
  public function __construct(array $configuration, string $plugin_id, array $plugin_definition, protected ApiManager $apiManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) : static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('api_tools.rest.api_manager')
    );
  }

  /**
   * Sends and processes a single request.
   *
   * @param \Drupal\api_tools\Request\Request $request
   *   The request to send.
   * @param callable $callback
   *   The callback to process response.
   *
   * @return \Drupal\api_tools\Response\Response
   *   The response.
   *
   * @throws \Drupal\api_tools\Exception\ErrorResponseException
   */
  public function request(Request $request, callable $callback) : Response {
    return $this->requestMultiple([$request], $callback)->current();
  }

  /**
   * Sends and processes multiple requests.
   *
   * @phpcs:disable Drupal.Commenting.FunctionComment.InvalidNoReturn
   *
   * @param \Drupal\api_tools\Request\Request[] $requests
   *   The requests.
   * @param callable $callback
   *   The callback to process responses.
   *
   * @yield \Drupal\api_tools\Response\Response[]
   *   The response.
   *
   * @throws \Drupal\api_tools\Exception\ErrorResponseException
   */
  public function requestMultiple(array $requests, callable $callback): \Generator {
    $promises = [];

    foreach ($requests as $request) {
      $promises[] = $this->apiManager->createPromise($request);
    }

    yield from $this->apiManager->handlePromises($promises, $callback);
  }

}
