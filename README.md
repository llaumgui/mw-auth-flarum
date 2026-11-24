# AuthFlarum

[![Author][ico-bluesky]][link-bluesky]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-ghactions]][link-ghactions]
[![MediaWiki Version][ico-mediawiki]][link-mediawiki]
[![Latest Version][ico-version]][link-packagist]

[![Quality Gate Status][ico-sonarcloud-gate]][link-sonarcloud-gate]
[![Maintainability Rating][ico-sonarcloud-maintainability]][link-sonarcloud-maintainability]
[![Reliability Rating][ico-sonarcloud-reliability]][link-sonarcloud-reliability]
[![Security Rating][ico-sonarcloud-security]][link-sonarcloud-security]

Authenticates MediaWiki users against a [Flarum](https://flarum.org/) account. It supports Flarum 1 and Flarum 2, optional account creation, a minimum post count for new accounts, and optional Flarum 2FA tokens.

## Requirements

- MediaWiki 1.43 or newer
- PHP 8.3 or newer
- A reachable Flarum instance
- Flarum API access

Authentication data is provided by Flarum. Password changes and property changes must be performed in Flarum rather than MediaWiki.

## Installation

### From Git

Clone the extension into MediaWiki's `extensions` directory:

```bash
cd extensions
git clone https://github.com/llaumgui/mw-auth-flarum.git AuthFlarum
```

Then load the extension from `LocalSettings.php`:

```php
wfLoadExtension( 'AuthFlarum' );
```

## Configuration

### `$wgAuthFlarumUri`

The base URI of the Flarum instance.

```php
$wgAuthFlarumUri = 'https://forum.example.org';
```

### `$wgAuthFlarumAutoCreate`

Controls whether a successful Flarum login automatically creates the corresponding MediaWiki account. The default is `true`.

To allow account creation while preventing users from creating MediaWiki accounts through the normal registration flow:

```php
$wgGroupPermissions['*']['autocreateaccount'] = true;
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['sysop']['createaccount'] = false;
$wgAuthFlarumAutoCreate = true;
```

### `$wgAuthFlarumAutoCreateMinPost`

Sets the minimum number of Flarum comments required before MediaWiki can create an account. The default is `100`.

```php
$wgAuthFlarumAutoCreateMinPost = 100;
```

Set this to `0` to allow account creation without a minimum comment count.

### `$wgAuthFlarumEnable2FA`

Adds an optional two-factor authentication field to the login form.

```php
$wgAuthFlarumEnable2FA = true;
```

Enable this setting only when 2FA is configured on the Flarum instance. When enabled, users with 2FA configured must enter their Flarum 2FA token during login.

## Example configuration

```php
wfLoadExtension( 'AuthFlarum' );

$wgAuthFlarumUri = 'https://forum.example.org';
$wgAuthFlarumAutoCreate = true;
$wgAuthFlarumAutoCreateMinPost = 50;
$wgAuthFlarumEnable2FA = true;

$wgGroupPermissions['*']['autocreateaccount'] = true;
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['sysop']['createaccount'] = false;
```

## Development

Install development dependencies and run the project checks:

```bash
composer install
composer test
composer phpunit
composer phan
```

`composer phpunit` displays the code coverage summary and generates the HTML
report in `coverage/index.html`.

## License

AuthFlarum is licensed under the [GNU General Public License v2.0 or later](LICENSE).

[ico-bluesky]: https://img.shields.io/static/v1?label=Author&message=llaumgui&color=208bfe&logo=bluesky&style=flat-square
[link-bluesky]: https://bsky.app/profile/llaumgui.kulakowski.fr
[ico-mediawiki]: https://img.shields.io/static/v1?label=mediawiki&message=%E2%89%A51.43.0&color=cd1f44&logo=wikipedia&style=flat-square
[link-mediawiki]: https://www.mediawiki.org/
[ico-ghactions]: https://img.shields.io/github/actions/workflow/status/llaumgui/mw-auth-flarum/devops.yml?branch=main&style=flat-square&logo=github&label=Tests
[link-ghactions]: https://github.com/llaumgui/mw-auth-flarum/actions
[ico-version]: https://img.shields.io/packagist/v/llaumgui/mw-auth-flarum.svg?include_prereleases&label=Package%20version&style=flat-square&logo=packagist
[link-packagist]: https://packagist.org/packages/llaumgui/mw-auth-flarum
[ico-license]: https://img.shields.io/github/license/llaumgui/mw-auth-flarum?style=flat-square
[ico-sonarcloud-gate]: https://sonarcloud.io/api/project_badges/measure?branch=main&project=llaumgui_mw-auth-flarum&metric=alert_status
[link-sonarcloud-gate]: https://sonarcloud.io/dashboard?id=llaumgui_mw-auth-flarum&branch=main
[ico-sonarcloud-maintainability]: https://sonarcloud.io/api/project_badges/measure?project=llaumgui_mw-auth-flarum&metric=sqale_rating
[link-sonarcloud-maintainability]: https://sonarcloud.io/dashboard?id=llaumgui_mw-auth-flarum
[ico-sonarcloud-reliability]: https://sonarcloud.io/api/project_badges/measure?project=llaumgui_mw-auth-flarum&metric=reliability_rating
[link-sonarcloud-reliability]: https://sonarcloud.io/dashboard?id=llaumgui_mw-auth-flarum
[ico-sonarcloud-security]: https://sonarcloud.io/api/project_badges/measure?project=llaumgui_mw-auth-flarum&metric=security_rating
[link-sonarcloud-security]: https://sonarcloud.io/dashboard?id=llaumgui_mw-auth-flarum
