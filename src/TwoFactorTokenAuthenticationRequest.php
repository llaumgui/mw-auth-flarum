<?php

/*
 * This file is part of the AuthFlarum package.
 *
 * Copyright (C) 2021 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AuthFlarum;

use MediaWiki\Auth\AuthenticationRequest;

/**
 * Authentication request for an optional Flarum two-factor token.
 */
class TwoFactorTokenAuthenticationRequest extends AuthenticationRequest {
	/** @var string|null */
	public $twoFactorToken = null;

	/**
	 * @return array
	 */
	public function getFieldInfo() {
		return [
			'twoFactorToken' => [
				'type' => 'string',
				'label' => wfMessage( 'authflarum-two-factor-token-label' ),
				'optional' => true,
				'sensitive' => true,
			],
		];
	}
}
