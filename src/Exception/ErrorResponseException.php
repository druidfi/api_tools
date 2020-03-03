<?php

declare(strict_types = 1);

namespace Drupal\druidfi_api_tools\Exception;

use Drupal\druidfi_api_tools\Response\ErrorResponse;
use Throwable;

/**
 * An exception for ErrorResponse responses.
 */
final class ErrorResponseException extends \Exception {

  /**
   * The response.
   *
   * @var \Drupal\druidfi_api_tools\Response\ErrorResponse
   */
  private $response;

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\druidfi_api_tools\Response\ErrorResponse $response
   *   The error response.
   * @param \Throwable|null $previous
   *   The throwable.
   */
  public function __construct(ErrorResponse $response, Throwable $previous = NULL) {
    $this->response = $response;

    $message = '';

    if ($response->getResponseDebug()) {
      $message = $response->getResponseDebug()->getDescription();
    }
    parent::__construct($message, 0, $previous);
  }

  /**
   * Gets the response.
   *
   * @return \Drupal\druidfi_api_tools\Response\ErrorResponse
   *   The response.
   */
  public function getResposne(): ErrorResponse {
    return $this->response;
  }

}
