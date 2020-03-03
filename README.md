# Druidfi API Tools

[![Build Status](https://travis-ci.com/druidfi/druidfi_api_tools.svg?branch=8.x-1.x)](https://travis-ci.com/druidfi/druidfi_api_tools)

## Usage

See [modules/druidfi_api_tools_example](modules/druidfi_api_tools_example) for a complete example.

At minimum, you have to create `@RestApiRequest` plugin and a corresponding Response object. 

For example: 

`yourmodule/src/Plugin/RestApiRequest/Example.php`:

```php
<?php

declare(strict_types = 1);

namespace Drupal\yourmodule\Plugin\RestApiRequest;

use Drupal\druidfi_api_tools\Request\Request;
use Drupal\druidfi_api_tools\Rest\ApiRequestBase;
use Drupal\yourmodule\Response\ExampleResponse;
use Generator;
use League\Uri\Uri;
use Psr\Http\Message\ResponseInterface;

/**
 * @RestApiRequest(
 *   id = "example",
 * )
 */
final class Example extends ApiRequestBase {

  private function getUri(int $id) : Uri {
    return Uri::createFromString('https://example.com/api/v1/endpoint/' . $id);
  }

  public function getPost(int $id) : ExampleResponse {
    return $this->getMultiplePosts([$id])->current();
  }

  public function getMultiplePosts(array $ids) : Generator {
    $requests = [];
    
    foreach ($ids as $id) {
      $requests[] = new Request($this->getUri($id));
    }
    
    // Send all requests asynchronously and wait for all of them to finish.
    yield from $this->requestMultiple($requests, function (ResponseInterface $response) {
      $json = \GuzzleHttp\json_decode($response->getBody()->getContents());

      // We assume that the API above returns data like this:
      // [
      //   'entities' => [
      //      ['id' => 1...],
      //      ['id' => 2...],
      //      ...
      //   ]
      // ]
      // Parse the json and return corresponding Response object.
      yield new ExampleResponse($json->entities);
    });
  }
}

```

`yourmodule/src/Response/ExampleResponse.php`:

```php
<?php

declare(strict_types = 1);

namespace Drupal\yourmodule\Response;

use Drupal\druidfi_api_tools\Response\SuccessResponse;

/**
 * Provides an example response.
 */
final class ExampleResponse extends SuccessResponse {

  /**
   * The entities.
   *
   * @var array
   */
  protected $entities = [];

  /**
   * Constructs a new instance.
   *
   * @param \stdClass[] $entities
   *   The entities.
   */
  public function __construct(array $entities) {
    $this->entities = $entities;
  }

  /**
   * Gets the entities.
   *
   * @return array|\stdClass[]
   *   The entities.
   */
  public function getEntities() : array {
    return $this->entities;
  }

}
```

To use the rest plugin:

```php
<?php

/** @var \Drupal\druidfi_api_tools\Rest\RequestFactory $factory */
$factory = \Drupal::service('druidfi_api_tools.rest.request_factory');
$manager = $factory->create('example');
/** @var \Drupal\yourmodule\Response\ExampleResponse $postResponse */
$postResponse = $manager->getPost(1);
$posts = $postResponse->getEntities();
```

@todo

