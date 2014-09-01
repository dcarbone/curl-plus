curl-plus
======

A simple wrapper around PHP's cURL implementation.  It does not do anything magical, and is intended to be as simple as possible

# Installation

For convenience, I have provided two primary methods for usage within your application.  You can of course use whatever mechanism
you prefer for loading classes into your app.

## Inclusion in your Composer app

Add

```
"dcarbone/curl-plus" : "0.4.*"
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

And now you may simply execute:

```php
$curlPlusClient = new CurlPlusClient();

// Rest of code
```

...and all classes will be automatically found and loaded by php for use!

# Basic Usage

The most simple implementation of this class would be something like the following:

```php
use DCarbone\CurlPlus\CurlPlusClient;

$client = new CurlPlusClient(
    'http://my-url.etc/api',
    array(
        CURLOPT_RETURNTRANSFER => true,
    ));


// Returns \DCarbone\CurlPlus\Response\CurlPlusResponse object
$response = $client->execute();

echo $response->getResponse()."\n";
var_dump($response->getError())."\n";
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

## Interface

On many occasions curl is used as part of another class's operation.  In order to help facilitate this, I have provided an interface for
your curl-containing class called `ICurlPlusContainer` that the `CurlPlusClient` class itself actually implements.

The usage of this interface is optional, and only serves to provide a standard implementation mechanism.

### Bumf

If you have any suggestions or criticisms of this library, things I could do to make it more useful for you, etc, please let me know.

I always enjoy a good challenge :)