# AuthFlarum

[![Tests](https://github.com/llaumgui/mw-auth-flarum/actions/workflows/qa.yaml/badge.svg)](https://github.com/llaumgui/mw-auth-flarum/actions/workflows/qa.yaml)<br />
[![GitHub license](https://img.shields.io/github/license/llaumgui/mw-auth-flarum.svg)](https://github.com/llaumgui/mw-auth-flarum/blob/main/LICENSE) [![PHP version](https://badge.fury.io/ph/llaumgui%2Fmw-auth-flarum.svg)](https://packagist.org/packages/llaumgui/mw-auth-flarum)<br />
[![Average time to resolve an issue](http://isitmaintained.com/badge/resolution/llaumgui/mw-auth-flarum.svg)](http://isitmaintained.com/project/llaumgui/mw-auth-flarum "Average time to resolve an issue") [![Percentage of issues still open](http://isitmaintained.com/badge/open/llaumgui/mw-auth-flarum.svg)](http://isitmaintained.com/project/llaumgui/mw-auth-flarum "Percentage of issues still open")



Allows to connect to MediaWiki from an a ccount based on the [Flarum](https://flarum.org/) forum solution.

## Installation

1. Put code in extensions/AuthFlarum.
2. Enable extension :

```php
wfLoadExtension( 'AuthFlarum' );
```

## Configuration

### `$wgAuthFlarumUri`

URI of your Flarum instance.

Example:

```php
$wgAuthFlarumUri = 'http://localhost';
```

### `$wgAuthFlarumAutoCreate`

Allow auto creation of MediaWiki account from Flarum ? Becarrefull, also Need

```php
$wgGroupPermissions['*']['autocreateaccount'] = true;
```

Example:

```php
$wgAuthFlarumAutoCreate = true;
```

### `$wgAuthFlarumAutoCreateMinPost`

Need a minimum number of posts to allow auto creation of MediaWiki account.

Example:

```php
$wgAuthFlarumAutoCreateMinPost = 100;
```
