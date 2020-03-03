<?php

declare(strict_types = 1);

namespace Drupal\druidfi_api_tools\Response;

/**
 * Base class for responses.
 */
abstract class Response {

  /**
   * The debugging information.
   *
   * @var \Drupal\druidfi_api_tools\Response\Debug
   */
  private $debug;

  /**
   * Creates a new request with this debugging information.
   *
   * @param \Drupal\druidfi_api_tools\Response\Debug $debug
   *   The debug.
   *
   * @return self
   *   The self.
   */
  public function withResponseDebug(Debug $debug) : self {
    $response = clone $this;
    $response->debug = $debug;
    return $response;
  }

  /**
   * Gets the debugging information.
   *
   * @return \Drupal\druidfi_api_tools\Response\Debug|null
   *   The debug or null.
   */
  public function getResponseDebug() : ? Debug {
    return $this->debug;
  }

}
