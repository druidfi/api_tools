<?php

namespace Drupal\druidfi_api_tools_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Returns responses for Druidfi API tools example routes.
 */
class ExampleController extends ControllerBase {

  /**
   * Builds the response.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The json response.
   */
  public function get(Request $request) {
    $response = [
      'method' => $request->getMethod(),
    ];

    if ($request->getMethod() === 'POST') {
      return new JsonResponse($response + [
        'method' => $request->getMethod(),
        // Send entity back as it is.
        'entity' => \GuzzleHttp\json_decode($request->getContent()),
      ]);
    }
    return new JsonResponse($response + [
      'entities' => [
        ['id' => 1, 'title' => 'Test'],
        ['id' => 2, 'title' => 'Test 2'],
        ['id' => 3, 'title' => 'Test 3'],
      ],
    ]);
  }

}
