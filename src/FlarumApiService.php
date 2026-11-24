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

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * FlarumUser class.
 */
class FlarumApiService {

	/**
	 * Flarum API URI.
	 * @var string
	 */
	private string $flarumApiUri;
	/**
	 * The HttpRequestFactory component.
	 * @var GuzzleClient
	 */
	private GuzzleClient $guzzleClient;
	/**
	 * Flarum user Uid
	 * @var int
	 */
	private int $id = 0;
	/**
	 * Flarum API token
	 * @var string
	 */
	private string $token = '';

	/**
	 * FlarumApiService constructor.
	 */
	public function __construct( string $flarumApiUri, GuzzleClient $guzzleClient ) {
		$this->flarumApiUri = rtrim( $flarumApiUri, '/' );
		$this->guzzleClient = $guzzleClient;
	}

	/**
	 * Connect to Flarum API.
	 *
	 * @param string $username Flarum username
	 * @param string $password Flarum password
	 * @param string|null $twoFactorToken Flarum two-factor token
	 * @return int Flarum user Uid
	 */
	public function connect( string $username, string $password, $twoFactorToken = null ): int {
		$form_params = [
			'identification' => $username,
			'password' => $password
		];
		if ( $twoFactorToken !== null ) {
			$form_params['twoFactorToken'] = $twoFactorToken;
		}

		try {
			$response = $this->guzzleClient->request( 'POST', $this->flarumApiUri . '/api/token', [
				'form_params' => $form_params
			] );
		} catch ( GuzzleException $e ) {
			// Pass Guzzle exception to use AuthenticationResponse::newAbstain();
			return 0;
		}

		if ( $response->getStatusCode() !== 200 ) {
			return 0;
		}
		$json = json_decode( (string)$response->getBody(), true );
		$this->id = $json['userId'];
		$this->token = $json['token'];

		return $this->id;
	}

	/**
	 * Get all flarum user information
	 * @param int $uid Flarum user uid
	 * @return array|false Array Flarum user informations
	 */
	public function getUserInfo( int $uid ): ?array {
		$response = $this->guzzleClient->request( 'GET', $this->flarumApiUri . '/api/users/' . $uid, [
				'headers' => [
					'Authorization' => 'Token ' . $this->token
				]
		] );
		if ( $response->getStatusCode() === 200	) {
			$json = json_decode( (string)$response->getBody(), true );

			$userGroups = [];
			foreach ( $json['data']['relationships']['groups']['data'] as $group ) {
				if ( $group['type'] == 'groups' ) {
					$userGroups[] = $group['id'];
				}
			}
			$userGroups = [ 'userGroups' => $userGroups ];

			return array_merge( $json['data']['attributes'], $userGroups );
		}
		return null;
	}
}
