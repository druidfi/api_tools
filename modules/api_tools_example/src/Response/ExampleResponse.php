<?php

declare(strict_types = 1);

namespace Drupal\api_tools_example\Response;

use Drupal\api_tools\Response\SuccessResponse;
use Drupal\api_tools_example\Mock\ResponseEntity;
use Webmozart\Assert\Assert;

/**
 * Provides an example response.
 */
final class ExampleResponse extends SuccessResponse {

  /**
   * The entities.
   *
   * @var array|\Drupal\api_tools_example\Mock\ResponseEntity[]
   */
  protected array $entities = [];

  /**
   * Constructs a new instance.
   *
   * @param \Drupal\api_tools_example\Mock\ResponseEntity[] $entities
   *   The entities.
   */
  public function __construct(array $entities) {
    Assert::allIsInstanceOf($entities, ResponseEntity::class);

    $this->entities = $entities;
  }

  /**
   * Adds the given entity.
   *
   * @param \Drupal\api_tools_example\Mock\ResponseEntity $entity
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
   * @return \Drupal\api_tools_example\Mock\ResponseEntity[]
   *   The entities.
   */
  public function getEntities() : array {
    return $this->entities;
  }

}
