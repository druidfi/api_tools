<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Rest;

use Drupal\api_tools\Exception\ErrorResponseException;
use Drupal\api_tools\Request\Request;
use Drupal\api_tools\Response\Debug;
use Drupal\api_tools\Response\ErrorResponse;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;

/**
 * Provides connector for sending API requests to Portal API.
 */
final class ApiManager {

  /**
   * Constructs a new instance.
   *
   * @param \GuzzleHttp\ClientInterface $client
   *   The http client.
   */
  public function __construct(private ClientInterface $client) {
  }

  /**
   * Handles the request promises.
   *
   * @phpcs:disable Drupal.Commenting.FunctionComment.InvalidNoReturn
   *
   * @param \GuzzleHttp\Promise\PromiseInterface[] $promises
   *   The promises.
   * @param callable $callable
   *   The formatter callback.
   *
   * @return \Generator|\Psr\Http\Message\ResponseInterface
   *   The responses or formatted responses.
   *
   * @throws \Drupal\api_tools\Exception\ErrorResponseException
   */
  public function handlePromises(array $promises, callable $callable) : \Generator {
    // Wait all promises to be finished.
    try {
      $results = Utils::unwrap($promises);

      foreach ($results as $result) {
        yield from $callable($result);
      }
    }
    catch (ClientException $e) {
      /** @var \Drupal\api_tools\Response\ErrorResponse $response */
      $response = (new ErrorResponse())
        ->withResponseDebug(
          new Debug($e->getResponse()->getBody()->getContents())
        );
      throw new ErrorResponseException($response);
    }
    catch (\Exception | \Throwable $e) {
      /** @var \Drupal\api_tools\Response\ErrorResponse $response */
      $response = (new ErrorResponse())
        ->withResponseDebug(
          new Debug($e->getMessage())
        );
      throw new ErrorResponseException($response);
    }
  }

  /**
   * Creates a new promise for given request object.
   *
   * @param \Drupal\api_tools\Request\Request $request
   *   The request object.
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   *   The promise container.
   */
  public function createPromise(Request $request): PromiseInterface {
    return $this->client->requestAsync($request->getMethod(), (string) $request->getUri(), $request->getOptions());
  }

}
