# AuthFlarum

[![Tests](https://github.com/llaumgui/mw-auth-flarum/actions/workflows/qa.yaml/badge.svg)](https://github.com/llaumgui/mw-auth-flarum/actions/workflows/qa.yaml)

Allow to connect to MediaWiki from [Flarum](https://flarum.org/) account.

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
