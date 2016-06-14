# Racoon Router

[![Build Status](https://travis-ci.org/TomWright/RacoonRouter.svg?branch=master)](https://travis-ci.org/TomWright/RacoonRouter)
[![Total Downloads](https://poser.pugx.org/racoon/router/d/total.svg)](https://packagist.org/packages/racoon/router)
[![Latest Stable Version](https://poser.pugx.org/racoon/router/v/stable.svg)](https://packagist.org/packages/racoon/router)
[![Latest Unstable Version](https://poser.pugx.org/racoon/router/v/unstable.svg)](https://packagist.org/packages/racoon/router)
[![License](https://poser.pugx.org/racoon/router/license.svg)](https://packagist.org/packages/racoon/router)

## Routing
Racoon uses [nikic/fast-route][nikic/fast-route] to deal with routing.

### Defining where routes are stored

Routes need to be added in a routes file, which should be added to the `Router`, or defined in an anonymous function passed to the router.

```php
$router->addRouteFile('/path/to/some_routes.php');

// Tells the router you are done adding routes so as it can process them.
$router->init();
```

If you want to store routes in multiple locations you can do it as follows.

```php
$router
    ->addRouteFile('/path/to/some_routes.php')
    ->addRouteFile('/path/to/more_routes.php')
    ->addRouteFile('/path/to/even_more_routes.php');

// Tells the router you are done adding routes so as it can process them.
$router->init();
```

If you define multiple route locations, they will be included/added in the same order as you define them.

To define routes without storing them in a separate file you can do the following.

```php
$router->addRouteCallable(function($r) {
    // Define your routes here as if they were in another file.
    // $r->addRoute(), $r->addGroup(), etc are all available.
});
```

### Setting up routes
Inside one of the route files that have been added to the router you need to define your routes in the following format.

```php
$httpRequestMethod = ['GET', 'POST'];
$requestUri = '/users/list';
$handlerString = '\\MyApp\\Users@list';
$r->addRoute($httpRequestMethod, $requestUri, $handlerString);
```

#### Route Groups
To make it easier to set up long urls multiple times you can use groups. The following code blocks give the same result.

```php
$r->addRoute(['GET', 'POST'], '/users/list', '\\MyApp\\Users@list');
$r->addRoute(['GET', 'POST'], '/users/get', '\\MyApp\\Users@get');
$r->addRoute(['GET', 'POST'], '/users/update', '\\MyApp\\Users@update');
$r->addRoute(['GET', 'POST'], '/users/delete', '\\MyApp\\Users@delete');
```

```php
$r->addGroup('/users', function () {
    $r->addRoute(['GET', 'POST'], '/list', '\\MyApp\\Users@list');
    $r->addRoute(['GET', 'POST'], '/get', '\\MyApp\\Users@get');
    $r->addRoute(['GET', 'POST'], '/update', '\\MyApp\\Users@update');
    $r->addRoute(['GET', 'POST'], '/delete', '\\MyApp\\Users@delete');
});
```

You can also use sub-groups. The following code blocks give the same result.

```php
$r->addRoute(['GET', 'POST'], '/some/long/url/do-something', '\\MyApp\\Users@list');
```

```php
$r->addGroup('/some', function ($r) {
    $r->addGroup('/long', function ($r) {
        $r->addGroup('/url', function ($r) {
            $r->addRoute(['GET', 'POST'], '/do-something', '\\MyApp\\Users@list');
        });
    });
});
```

#### HTTP Request Method
The HTTP Request Method(s) that the route should match. This can be any HTTP request type such as `GET` or `POST`.

Can be a `string` or an `array` of `string`s.

#### Request URI
The request URI that the route should match.

You can define the request URI in multiple ways.

```php
'/users/list'
'/users/get/{userId}'
'/users/get/{userId:\d+}'
```

For more information see the [FastRoute Route Docs][fastroute-route-docs]

Any wildcards/placeholders defined here will be passed into the Controller/Handler method.

#### Handler String
The handler string defines the class and method that should be executed should the current request match a route.

The required format is `\MyApp\Users@list` where `\MyApp\Users` is the full class name including the namespace, and `list` is the method inside of that class which you want to be executed.

[nikic/fast-route]: https://github.com/nikic/FastRoute
[fastroute-route-docs]: https://github.com/nikic/FastRoute#defining-routes