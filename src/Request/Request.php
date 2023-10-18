<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Request;

use League\Uri\Contracts\UriInterface;

/**
 * Base class for API requests.
 */
class Request {

  /**
   * Constructs a new instance.
   *
   * @param \League\Uri\Contracts\UriInterface $uri
   *   The uri.
   * @param string $method
   *   The HTTP method.
   * @param array $options
   *   The request options.
   */
  public function __construct(
    private UriInterface $uri,
    private string $method = 'GET',
    private array $options = []
  ) {
  }

  /**
   * Gets the HTTP method.
   *
   * @return string
   *   The HTTP method.
   */
  public function getMethod() : string {
    return $this->method;
  }

  /**
   * Gets the uri.
   *
   * @return \League\Uri\Contracts\UriInterface
   *   The uri.
   */
  public function getUri() : UriInterface {
    return $this->uri;
  }

  /**
   * Gets the request options.
   *
   * @return array
   *   The options.
   */
  public function getOptions() : array {
    return $this->options;
  }

}
