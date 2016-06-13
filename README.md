# Racoon Router

[![Build Status](https://travis-ci.org/TomWright/RacoonRouter.svg?branch=master)](https://travis-ci.org/TomWright/RacoonRouter)
[![Total Downloads](https://poser.pugx.org/racoon/router/d/total.svg)](https://packagist.org/packages/racoon/router)
[![Latest Stable Version](https://poser.pugx.org/racoon/router/v/stable.svg)](https://packagist.org/packages/racoon/router)
[![Latest Unstable Version](https://poser.pugx.org/racoon/router/v/unstable.svg)](https://packagist.org/packages/racoon/router)
[![License](https://poser.pugx.org/racoon/router/license.svg)](https://packagist.org/packages/racoon/router)

## Routing
Racoon uses [nikic/fast-route][nikic/fast-route] to deal with routing.

### Defining where routes are stored

Routes need to be added in a routes file, which should be added to the `Router`.

```php
$router->addRouteFile('/path/to/some_routes.php');
```

If you want to store routes in multiple locations you can do it as follows.

```php
$router = $app->getRouter();

$router
    ->addRouteFile('/path/to/some_routes.php')
    ->addRouteFile('/path/to/more_routes.php')
    ->addRouteFile('/path/to/even_more_routes.php');
```

If you define multiple route locations, they will be included/added in the same order as you define them.

### Setting up routes
Inside one of the route files that have been added to the router you need to define your routes in the following format.

```php
$httpRequestMethod = ['GET', 'POST'];
$requestUri = '/users/list';
$handlerString = '\\MyApp\\Users@list';
$r->addRoute($httpRequestMethod, $requestUri, $handlerString);
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