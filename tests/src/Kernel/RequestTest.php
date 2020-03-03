<?php

declare(strict_types = 1);

namespace Drupal\Tests\druidfi_api_tools\Kernel;

use Drupal\druidfi_api_tools\Rest\RequestFactory;
use Drupal\druidfi_api_tools_example\Plugin\RestApiRequest\Example;
use Drupal\druidfi_api_tools_example\Response\ExampleResponse;
use Drupal\KernelTests\KernelTestBase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Tests requests.
 *
 * @group druidfi_api_tools
 */
class RequestTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'druidfi_api_tools',
    'druidfi_api_tools_example',
  ];

  /**
   * Gets the request factory mock.
   *
   * @param \GuzzleHttp\Psr7\Response[] $responses
   *   The responses.
   *
   * @return \Drupal\druidfi_api_tools\Rest\RequestFactory
   *   The request factory.
   */
  private function getMockRequestFactory(array $responses) : RequestFactory {
    $mock = new MockHandler($responses);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);
    $this->container->set('http_client', $client);

    /** @var \Drupal\druidfi_api_tools\Rest\RequestFactory $requestFactory */
    $requestFactory = $this->container->get('druidfi_api_tools.rest.request_factory');

    return $requestFactory;
  }

  /**
   * Tests get request.
   */
  public function testGetRequest() {
    $requestFactory = $this->getMockRequestFactory([
      new Response(200, [], json_encode([
        'entities' => [
          ['id' => 1, 'title' => 'Test'],
          ['id' => 2, 'title' => 'Test 2'],
        ],
      ])),
    ]);
    /** @var \Drupal\druidfi_api_tools_example\Plugin\RestApiRequest\Example $manager */
    $manager = $requestFactory->create(Example::class);
    /** @var \Drupal\druidfi_api_tools_example\Response\ExampleResponse $response */
    $response = $manager->getExampleData();

    $this->assertInstanceOf(ExampleResponse::class, $response);
    $this->assertEquals(2, count($response->getEntities()));
    $this->assertEquals('Test 2', $response->getEntities()[1]->title);
  }

  /**
   * Tests multiple get requests.
   */
  public function testGetRequests() {
    $requestFactory = $this->getMockRequestFactory([
      new Response(200, [], json_encode([
        'entities' => [
          ['id' => 1, 'title' => 'Test'],
          ['id' => 2, 'title' => 'Test 2'],
        ],
      ])),
      new Response(200, [], json_encode([
        'entities' => [
          ['id' => 3, 'title' => 'Test 3'],
          ['id' => 4, 'title' => 'Test 4'],
        ],
      ])),
      new Response(200, [], json_encode([
        'entities' => [
          ['id' => 5, 'title' => 'Test 5'],
          ['id' => 6, 'title' => 'Test 6'],
        ],
      ])),
    ]);
    /** @var \Drupal\druidfi_api_tools_example\Plugin\RestApiRequest\Example $manager */
    $manager = $requestFactory->create(Example::class);
    /** @var \Drupal\druidfi_api_tools_example\Response\ExampleResponse[] $responses */
    $responses = $manager->getMultipleExampleData(3);

    $processedResponses = 0;

    foreach ($responses as $response) {
      $this->assertInstanceOf(ExampleResponse::class, $response);
      $this->assertEquals(2, count($response->getEntities()));

      $processedResponses++;
    }

    $this->assertEquals(3, $processedResponses);
  }

  /**
   * Tests a single post request.
   */
  public function testPostRequest() {
    $requestFactory = $this->getMockRequestFactory([
      new Response(200, [], json_encode([
        'entity' => ['id' => 10, 'title' => 'Test 2'],
      ])),
    ]);
    /** @var \Drupal\druidfi_api_tools_example\Plugin\RestApiRequest\Example $manager */
    $manager = $requestFactory->create(Example::class);
    /** @var \Drupal\druidfi_api_tools_example\Response\ExampleResponse $response */
    $response = $manager->postExampleData(['id' => 10, 'title' => 'Test2']);

    $this->assertInstanceOf(ExampleResponse::class, $response);
    $this->assertEquals(1, count($response->getEntities()));
    $this->assertEquals('Test 2', $response->getEntities()[0]->title);
  }

  public function testPostRequests() {
    $requestFactory = $this->getMockRequestFactory([
      new Response(200, [], json_encode([
        'entity' => ['id' => 1, 'title' => 'Test'],
      ])),
      new Response(200, [], json_encode([
        'entity' => ['id' => 3, 'title' => 'Test 3'],
      ])),
      new Response(200, [], json_encode([
        'entity' => ['id' => 5, 'title' => 'Test 5'],
      ])),
    ]);
    /** @var \Drupal\druidfi_api_tools_example\Plugin\RestApiRequest\Example $manager */
    $manager = $requestFactory->create(Example::class);
    $response = $manager->postMultipleExampleData([
      ['id' => 1, 'title' => 'Test'],
      ['id' => 3, 'title' => 'Test 3'],
      ['id' => 5, 'title' => 'Test 5'],
    ]);

    // var_dump($response);

    $this->assertInstanceOf(ExampleResponse::class, $response);
    $this->assertEquals(3, count($response->getEntities()));

  }

}

