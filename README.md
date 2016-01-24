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
"dcarbone/curl-plus" : "2.1.*"
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

# Usage

There are two ways you can use this library:

1. The [CURL Helper](documentation/HELPER.md)
2. The [CurlPlusClient](documentation/CLIENT.md)

### TODO:

1. More tests.
2. More documentation.
3. Implement request-sensitive response classes.

### Bumf

If you have any suggestions or criticisms of this library, things I could do to make it more useful for you, etc, please let me know.

I always enjoy a good challenge :)
