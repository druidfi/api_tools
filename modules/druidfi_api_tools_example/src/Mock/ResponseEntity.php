<?php

declare(strict_types = 1);

namespace Drupal\druidfi_api_tools_example\Mock;

/**
 * The mock entity.
 */
final class ResponseEntity {

  /**
   * The id.
   *
   * @var int
   */
  public $id;

  /**
   * The title.
   *
   * @var string
   */
  public $title;

  /**
   * Constructs a new instance.
   *
   * @param int $id
   *   The id.
   * @param string $title
   *   The title.
   */
  public function __construct(int $id, string $title) {
    $this->id = $id;
    $this->title = $title;
  }

}
