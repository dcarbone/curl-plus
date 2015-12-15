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

Check out the [CurlPlusClient source](../src/CurlPlusClient.php) for more information.