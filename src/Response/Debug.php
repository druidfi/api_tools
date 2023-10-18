<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Response;

/**
 * Defines an API response's debugging information.
 */
final class Debug {

  /**
   * Constructs a new instance.
   *
   * @param string $description
   *   The response description.
   * @param string[] $instructions
   *   The improvement instructions.
   */
  public function __construct(
    private string $description,
    private array $instructions = []
  ) {
  }

  /**
   * Gets the response description.
   *
   * @return string
   *   The description.
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * Gets the improvement instructions.
   *
   * @return string[]
   *   The improvement instructions.
   */
  public function getInstructions() : array {
    return $this->instructions;
  }

}
