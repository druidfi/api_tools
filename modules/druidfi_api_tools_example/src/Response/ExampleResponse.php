<?php

declare(strict_types = 1);

namespace Drupal\druidfi_api_tools_example\Response;

use Drupal\druidfi_api_tools\Response\SuccessResponse;
use Drupal\druidfi_api_tools_example\Mock\ResponseEntity;
use Webmozart\Assert\Assert;

/**
 * Provides an example response.
 */
final class ExampleResponse extends SuccessResponse {

  /**
   * The entities.
   *
   * @var array|\Drupal\druidfi_api_tools_example\Mock\ResponseEntity[]
   */
  protected $entities = [];

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\druidfi_api_tools_example\Mock\ResponseEntity[] $entities
   *   The entities.
   */
  public function __construct(array $entities) {
    Assert::allIsInstanceOf($entities, ResponseEntity::class);

    $this->entities = $entities;
  }

  /**
   * Adds the given entity.
   *
   * @param \Drupal\druidfi_api_tools_example\Mock\ResponseEntity $entity
   *   The entity.
   *
   * @return $this
   *   The self.
   */
  public function addEntity(ResponseEntity $entity) : self {
    $this->entities[] = $entity;

    return $this;
  }

  /**
   * Gets the entities.
   *
   * @return \Drupal\druidfi_api_tools_example\Mock\ResponseEntity[]
   *   The entities.
   */
  public function getEntities() : array {
    return $this->entities;
  }

}
