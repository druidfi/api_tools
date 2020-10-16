<?php

declare(strict_types = 1);

namespace Drupal\api_tools_example\Plugin\RestApiRequest;

use Drupal\Core\Url;
use Drupal\api_tools\Request\Request;
use Drupal\api_tools\Rest\ApiRequestBase;
use Drupal\api_tools_example\Mock\ResponseEntity;
use Drupal\api_tools_example\Response\ExampleResponse;
use Generator;
use League\Uri\Uri;
use Psr\Http\Message\ResponseInterface;

/**
 * Example API request.
 *
 * @RestApiRequest(
 *   id = "Drupal\api_tools_example\Plugin\RestApiRequest\Example",
 *   description = "Demostrates how to use 'api_tools' module.",
 * )
 */
final class Example extends ApiRequestBase {

  /**
   * Builds the URI.
   *
   * @return \League\Uri\Uri
   *   The uri.
   */
  private function getUri() : Uri {
    return Uri::createFromString(Url::fromRoute('api_tools_example.example')->toString());
  }

  /**
   * Gets the example data.
   *
   * @return \Drupal\api_tools_example\Response\ExampleResponse
   *   The response.
   */
  public function getExampleData() : ExampleResponse {
    return $this->getMultipleExampleData(1)->current();
  }

  /**
   * Sends multiple requests to example data endpoint.
   *
   * @phpcs:disable Drupal.Commenting.FunctionComment.InvalidNoReturn
   *
   * @param int $num
   *   The number of requests to make.
   *
   * @yield \Drupal\api_tools_example\Response\ExampleResponse
   *   The response.
   */
  public function getMultipleExampleData(int $num) : Generator {
    $requests = [];

    for ($i = 0; $i < $num; $i++) {
      $requests[] = new Request($this->getUri());
    }
    /** @var \Drupal\api_tools_example\Response\ExampleResponse[] $data */
    $data = $this->requestMultiple($requests, function (ResponseInterface $response) {
      $json = \GuzzleHttp\json_decode($response->getBody()->getContents());

      $entities = [];
      foreach ($json->entities as $item) {
        $entities[] = new ResponseEntity($item->id, $item->title);
      }
      yield new ExampleResponse($entities);
    });

    yield from $data;
  }

  /**
   * Posts the given data set.
   *
   * @param array $data
   *   The data to send.
   *
   * @return \Drupal\api_tools_example\Response\ExampleResponse
   *   The response.
   */
  public function postExampleData(array $data) : ExampleResponse {
    return $this->postMultipleExampleData([$data]);
  }

  /**
   * Posts multiple data sets.
   *
   * @param array $data
   *   The data to send.
   *
   * @return \Drupal\api_tools_example\Response\ExampleResponse
   *   The response.
   */
  public function postMultipleExampleData(array $data) : ExampleResponse {
    $requests = [];

    foreach ($data as $entity) {
      $requests[] = new Request($this->getUri(), 'POST', [
        'json' => $entity,
      ]);
    }

    $entities = $this->requestMultiple($requests, function (ResponseInterface $response) {
      $json = \GuzzleHttp\json_decode($response->getBody()->getContents());

      yield new ResponseEntity($json->entity->id, $json->entity->title);
    });

    // Combine multiple responses into a single ExampleResponse.
    $response = new ExampleResponse([]);

    foreach ($entities as $entity) {
      $response->addEntity($entity);
    }
    return $response;
  }

}
