<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Response;

/**
 * Base class for responses.
 */
abstract class Response {

  /**
   * The debugging information.
   *
   * @var \Drupal\api_tools\Response\Debug
   */
  private Debug $debug;

  /**
   * Creates a new request with this debugging information.
   *
   * @param \Drupal\api_tools\Response\Debug $debug
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
   * @return \Drupal\api_tools\Response\Debug|null
   *   The debug or null.
   */
  public function getResponseDebug() : ? Debug {
    return $this->debug;
  }

}
