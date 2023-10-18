<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Exception;

use Drupal\api_tools\Response\ErrorResponse;

/**
 * An exception for ErrorResponse responses.
 */
final class ErrorResponseException extends \Exception {

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\api_tools\Response\ErrorResponse $response
   *   The error response.
   * @param \Throwable|null $previous
   *   The throwable.
   */
  public function __construct(private ErrorResponse $response, \Throwable $previous = NULL) {
    $message = '';

    if ($response->getResponseDebug()) {
      $message = $response->getResponseDebug()->getDescription();
    }
    parent::__construct($message, 0, $previous);
  }

  /**
   * Gets the response.
   *
   * @return \Drupal\api_tools\Response\ErrorResponse
   *   The response.
   */
  public function getResponse(): ErrorResponse {
    return $this->response;
  }

}
