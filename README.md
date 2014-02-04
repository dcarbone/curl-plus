curl-plus
======

A simple OO wrapper around PHP's cURL implementation

This library is still considered to be in it's BETA phase.  Additional functionality will come as I get time.

#### Consumption

Add

```
"dcarbone/curl-plus" : "0.2.*"
```

To your application's ``` composer.json ``` file.

Learn more about Composer here: <a href="https://getcomposer.org/">https://getcomposer.org/</a>

#### Usage

```php
use DCarbone\CurlPlus;

$client = new CurlPlus\CurlPlusClient();

$client->setRequestUrl('your_url_here');
$client->setCurlOpt(CURLOPT_XXX, $value);
// add more opts

// Returns CurlPlus\Response\CurlResponse or CurlPlus\Error\CurlErrorBase as responses for now
$response = $client->execute();
```