# CurlPlus Helper

To make executing and retrieving data from HTTP requests easier, I have created the [CURL](../src/CURL.php) helper
class.

## Helper Basics:

All of the methods present below have a few default CURL options set, that you can override if needed:

- With the exception of HEAD and OPTIONS requests, all methods set ` CURLOPT_RETURNTRANSFER ` to true by default.
- ALL methods set ` CURLOPT_FOLLOWLOCATION ` to true by default.
- POST, PUT, and DELETE requests specify a default Request Content-Type header value of 
` application/x-www-form-urlencoded `
- POST, PUT, and DELETE requests allow specification of a request body.  This may either be a string,
or associative array of "param" => "value", or an object.  It will be turned into a string utilizing
[http_build_query](http://php.net/manual/en/function.http-build-query.php).
- ALL methods return an instance of either [CurlPlusResponse](../src/Response/CurlPlusResponse.php') or
[CurlPlusFileResponse](../src/Response/CurlPlusFileResponse.php) depending on your execution parameters.
- ALL methods allow overriding of CURLOPT and Request Header values used via optional array arguments.
These will be merged with the defaults, with user-specified values receiving preference.

## Usage Basics

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

Check out the source of [CURL](../src/CURL.php) to get a better look at all available methods and arguments.
