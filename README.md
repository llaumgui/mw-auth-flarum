# AuthFlarum

[![Author][ico-twitter]][link-twitter]
[![Build Status][ico-ghactions]][link-ghactions]
[![MediaWiki Version][ico-mediawiki]][link-mediawiki]
[![Latest Version][ico-version]][link-packagist]

[![Software License][ico-license]](LICENSE)

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
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['sysop']['createaccount'] = false;
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

[ico-twitter]: https://img.shields.io/static/v1?label=Author&message=llaumgui&color=50ABF1&logo=twitter&style=flat-square
[link-twitter]: https://twitter.com/llaumgui
[ico-mediawiki]: https://img.shields.io/static/v1?label=mediawiki&message=%E2%89%A51.36&color=cd1f44&logo=wikipedia&style=flat-square
[link-mediawiki]: https://www.mediawiki.org/
[ico-ghactions]: https://img.shields.io/github/actions/workflow/status/llaumgui/mw-auth-flarum/qa.yaml?branch=main&style=flat-square&logo=github&label=Tests
[link-ghactions]: https://github.com/llaumgui/mw-auth-flarum/actions
[ico-version]: https://img.shields.io/packagist/v/llaumgui/mw-auth-flarum.svg?include_prereleases&label=Package%20version&style=flat-square&logo=packagist
[link-packagist]: https://packagist.org/packages/llaumgui/mw-auth-flarum
[ico-license]: https://img.shields.io/github/license/llaumgui/mw-auth-flarum?style=flat-square
