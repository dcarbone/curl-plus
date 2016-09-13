curl-plus
======

A simple wrapper around PHP's cURL implementation.  It does not do anything magical, and is intended to be as simple as possible

Build status: [![Build Status](https://travis-ci.org/dcarbone/curl-plus.svg?branch=master)](https://travis-ci.org/dcarbone/curl-plus)

## Installation

This lib is designed to be used with [Composer](https://getcomposer.org/)

"require" entry:

```json
{
    "dcarbone/curl-plus" : "3.0.*"
}
```

## CurlPlus Helper

To make executing and retrieving data from HTTP requests easier, I have created the [CURL](./src/CURL.php) helper
class.

### Helper Basics:

All of the methods present below have a few default CURL options set, that you can override if needed:

- With the exception of HEAD and OPTIONS requests, all methods set ` CURLOPT_RETURNTRANSFER ` to true by default.
- ALL methods set ` CURLOPT_FOLLOWLOCATION ` to true by default.
- POST, PUT, and DELETE requests specify a default Request Content-Type header value of 
` application/x-www-form-urlencoded `
- POST, PUT, and DELETE requests allow specification of a request body.  This may either be a string,
or associative array of "param" => "value", or an object.  It will be turned into a string utilizing
[http_build_query](http://php.net/manual/en/function.http-build-query.php).
- ALL methods return an instance [CurlPlusResponse](./src/Response/CurlPlusResponse.php)
- ALL methods allow overriding of CURLOPT and Request Header values used via optional array arguments.
These will be merged with the defaults, with user-specified values receiving preference.

### Usage Basics

This class is as close as PHP gets to a "static" class.  It is abstract, and therefore cannot be
instantiated on its own, and is intended to be used as such:

```php
use DCarbone\CurlPlus\CURL;

$response = CURL::get('http://www.gstatic.com/hostedimg/6ce955e0e2197bb6_large');

$image = imagecreatefromstring((string)$response);

header('Content-Type: image/jpeg');
imagepng($image);
imagedestroy($image);
```

The above will download photographic evidence of life saving tamales and output the image.

Check out the source of [CURL](./src/CURL.php) to get a better look at all available methods and arguments.

## Using the client directly

The most simple implementation of this class would be something like the following:

```php
use DCarbone\CurlPlus\CURL;

$client = new CurlPlusClient(
    'http://my-url.etc/api',
    array(
        CURLOPT_RETURNTRANSFER => true,
    ));


// Returns \DCarbone\CurlPlus\CurlPlusResponse object
$response = $client->execute();

echo $response->responseBody."\n";
var_dump($response->responseHeaders);
var_dump($response->error);
```

The above will simply execute a GET request and store the response in memory, rather than in the output buffer.

### Constructor

The CurlPlusClient constructor takes 3 optional arguments:

* **$url** - string, the query endpoint
* **$curlOpts** - array, set of CURLOPT_ values
* **$requestHeaders** - array, associative array of $param=>$value strings that will be sent in the request

These are all optional parameters.

**Note**: If you set both the **$url** and CURLOPT_URL properties on construction, the constructor will use to the CURLOPT_URL value.

As stated above, all of those are optional arguments.  You may simply construct the object with no params if you wish.

Post-construct, the methods:

```php
// Set the URL you wish to query against, additionally you may also reset any existing curl opts
public function initialize($url, $reset = true) {}

// Accepts any options seen here: http://www.php.net//manual/en/function.curl-setopt.php
public function setCurlOpt($opt, $val) {}

// Accepts an associative array of Curl Opts
public function setCurlOpts(array $array) {}
```

...will probably be the ones you use the most often.

Check out the [CurlPlusClient source](./src/CurlPlusClient.php) for more information.