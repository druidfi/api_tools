# Druidfi API Tools

[![Tests](https://github.com/druidfi/api_tools/workflows/CI/badge.svg)](https://github.com/druidfi/api_tools/actions)

## Usage

See [modules/api_tools_example](modules/api_tools_example) for a complete example.

At minimum, you have to create `@RestApiRequest` plugin and a corresponding Response object.

For example:

`yourmodule/src/Plugin/RestApiRequest/Example.php`:

```php
<?php

declare(strict_types = 1);

namespace Drupal\yourmodule\Plugin\RestApiRequest;

use Drupal\api_tools\Request\Request;
use Drupal\api_tools\Rest\ApiRequestBase;
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

use Drupal\api_tools\Response\SuccessResponse;

final class ExampleResponse extends SuccessResponse {

  protected $entities = [];

  public function __construct(array $entities) {
    $this->entities = $entities;
  }

  public function getEntities() : array {
    return $this->entities;
  }

}
```

To use the rest plugin:

```php
<?php

/** @var \Drupal\api_tools\Rest\RequestFactory $factory */
$factory = \Drupal::service('api_tools.rest.request_factory');
$manager = $factory->create('example');
/** @var \Drupal\yourmodule\Response\ExampleResponse $postResponse */
$postResponse = $manager->getPost(1);
$posts = $postResponse->getEntities();
```

@todo

