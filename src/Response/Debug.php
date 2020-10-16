<?php

declare(strict_types = 1);

namespace Drupal\api_tools\Response;

/**
 * Defines an API response's debugging information.
 */
final class Debug {

  /**
   * The response description.
   *
   * @var string
   */
  protected $description;

  /**
   * The improvement instructions.
   *
   * @var string[]
   */
  protected $instructions = [];

  /**
   * Constructs a new instance.
   *
   * @param string $description
   *   The response description.
   * @param string[] $instructions
   *   The improvement instructions.
   */
  public function __construct(
    string $description,
    array $instructions = []
  ) {
    $this->description = $description;
    $this->instructions = $instructions;
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
   * Gets the improvement instructoins.
   *
   * @return string[]
   *   The improvement instructions.
   */
  public function getInstructions() {
    return $this->instructions;
  }

}
