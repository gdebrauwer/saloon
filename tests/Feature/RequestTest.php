<?php

declare(strict_types=1);

use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\PendingRequest;
use Saloon\Http\Response;
use Saloon\Http\Senders\GuzzleSender;
use Saloon\Tests\Fixtures\Connectors\TestConnector;
use Saloon\Tests\Fixtures\Requests\ErrorRequest;
use Saloon\Tests\Fixtures\Requests\ErrorRequestThatShouldBeTreatedAsSuccessful;
use Saloon\Tests\Fixtures\Requests\HasConnectorUserRequest;
use Saloon\Tests\Fixtures\Requests\UserRequest;

test('a request can be made successfully', function () {
    $connector = new TestConnector();
    $response = $connector->send(new UserRequest);

    $data = $response->json();

    expect($response->getPendingRequest()->isAsynchronous())->toBeFalse();
    expect($response)->toBeInstanceOf(Response::class);
    expect($response->isMocked())->toBeFalse();
    expect($response->status())->toEqual(200);

    expect($data)->toEqual([
        'name' => 'Sammyjo20',
        'actual_name' => 'Sam',
        'twitter' => '@carre_sam',
    ]);
});

test('a request can throw failed response exception', function () {
    $exception = null;

    try {
        (new TestConnector())->send(new ErrorRequest);
    } catch (RequestException $e) {
        $exception = $e;
    }

    expect($exception)->not->toBeNull();
    expect($exception->getResponse())->toBeInstanceOf(Response::class);
    expect($exception->getResponse()->status())->toEqual(500);
});

test('a request can handle an exception properly', function () {
    $connector = new TestConnector();
    $response = $connector->send(new ErrorRequest);

    expect($response->isMocked())->toBeFalse();
    expect($response->status())->toEqual(500);
});

test('a request with HasConnector can be sent individually', function () {
    $request = new HasConnectorUserRequest();

    expect($request->connector())->toBeInstanceOf(TestConnector::class);
    expect($request->sender())->toBeInstanceOf(GuzzleSender::class);
    expect($request->createPendingRequest())->toBeInstanceOf(PendingRequest::class);

    $response = $request->send();

    $data = $response->json();

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->isMocked())->toBeFalse();
    expect($response->status())->toEqual(200);

    expect($data)->toEqual([
        'name' => 'Sammyjo20',
        'actual_name' => 'Sam',
        'twitter' => '@carre_sam',
    ]);
});

test('a failed response that should be treated as a successful response', function () {
    $response = (new TestConnector)->send(new ErrorRequestThatShouldBeTreatedAsSuccessful);

    expect($response)->toBeInstanceOf(Response::class);
    expect($response->status())->toEqual(404);
});
