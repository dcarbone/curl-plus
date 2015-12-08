curl-plus
======

A simple wrapper around PHP's cURL implementation.  It does not do anything magical, and is intended to be as simple as possible

Build status: [![Build Status](https://travis-ci.org/dcarbone/curl-plus.svg?branch=master)](https://travis-ci.org/dcarbone/curl-plus)

# Installation

For convenience, I have provided two primary methods for usage within your application.  You can of course use whatever mechanism
you prefer for loading classes into your app.

## Inclusion in your Composer app

Add

```
"dcarbone/curl-plus" : "2.0.*"
```

To your application's ``` composer.json ``` file.

Learn more about Composer here: <a href="https://getcomposer.org/">https://getcomposer.org/</a>

## Inclusion in Non-composer app

In case you do not wish to use Composer to manage your dependencies, I have included an AutoLoader class for your
convenience.

Copy the **/src** directory to your location of choice, then execute:

```php
require '/path/to/curl-plus/src/CurlPlusAutoLoader.php';

CurlPlusAutoLoader::register();
```

# Using the Helper

To help reduce the amount of code you must write yourself, I have included a small "helper" class
with a number of public static functions whose names correspond with the request type.

For example:

```php
use DCarbone\CurlPlus\CURL;

$resp = (string)CURL::get('https://httpbin.org/get');
```

The above will execute a simple GET request against whatever URL you specify, returning a
[CurlPlusResponse](src/Response/CurlPlusResponse.php) object as the return value.  This can then be
type-cast to a string to just get the body of the response.

The other available methods on this helper are:

- post
- head
- options
- put
- delete

You can see the full implementation of this class [here](src/CURL.php).

Keep in mind that the request methods *head* and *options* do NOT return a body, only headers.

# Using the client directly

The most simple implementation of this class would be something like the following:

```php
use DCarbone\CurlPlus\CURL;

$client = new CurlPlusClient(
    'http://my-url.etc/api',
    array(
        CURLOPT_RETURNTRANSFER => true,
    ));


// Returns \DCarbone\CurlPlus\Response\CurlPlusResponse object
$response = $client->execute();

echo $response->getResponseBody()."\n";
var_dump($response->getResponseHeaders());
var_dump($response->getError());
echo "\n"
echo '<pre>';
var_dump($response->getInfo());
echo '</pre>';
```

The above will simply execute a GET request and store the response in memory, rather than in the output buffer.

## Constructor

The CurlPlusClient constructor takes 3 optional arguments:

* **$url** - string, the query endpoint
* **$curlOpts** - array, set of CURLOPT_ values
* **$requestHeaders** - array, array of strings that will be set as request headers

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

### TODO:

1. More tests.
2. More documentation.
3. Implement request-sensitive response classes.

### Bumf

If you have any suggestions or criticisms of this library, things I could do to make it more useful for you, etc, please let me know.

I always enjoy a good challenge :)
