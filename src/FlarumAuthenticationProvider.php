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

use MediaWiki\Auth\AbstractPasswordPrimaryAuthenticationProvider;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthenticationResponse;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserNameUtils;

/**
 * The Autentication provider.
 *
 * @see https://www.mediawiki.org/wiki/Manual:SessionManager_and_AuthManager/Updating_tips
 */
class FlarumAuthenticationProvider extends AbstractPasswordPrimaryAuthenticationProvider {
	/**
	 * Flarum user.
	 * @var FlarumUser
	 */
	private FlarumUser $flarumUser;

	/**
	 * Automatically create an account when asked to log in a
	 * Flarum user that does not exist in MediaWiki.
	 *
	 * @return bool
	 */
	public function accountCreationType() {
		return self::TYPE_CREATE;
	}

	/**
	 * Account creation from MediaWiki to flarum
	 *
	 * @param string $user
	 * @param stdObject $creator
	 * @param array $reqs
	 * @return AuthenticationResponse Response of authentication
	 */
	public function beginPrimaryAccountCreation( $user, $creator, array $reqs ) {
		return AuthenticationResponse::newFail( 'User creation is not allowed on the wiki. Pass by the forum.' );
	}

	/**
	 * Handle authentication: return PASS if the given credentials are good in Flarum,
	 * FAIL if they are bad, or ABSTAIN if they cannot be handled by this provider.
	 *
	 * @param array $reqs Query to authenticate
	 * @return AuthenticationResponse Response of authentication
	 */
	public function beginPrimaryAuthentication( array $reqs ) {
		$req = AuthenticationRequest::getRequestByClass( $reqs, PasswordAuthenticationRequest::class );

		if ( !$req || $req->username === null || $req->password === null ) {
			return AuthenticationResponse::newAbstain();
		}

		$userNameUtils = MediaWikiServices::getInstance()->getUserNameUtils();
		$username = $userNameUtils->getCanonical( $req->username, UserNameUtils::RIGOR_USABLE );
		if ( $username === false ) {
			return AuthenticationResponse::newAbstain();
		}

		$this->flarumUser = new FlarumUser( $username, $req->password );

		if ( $this->flarumUser->exists() ) {
				return AuthenticationResponse::newPass( $username );
		}

		return $this->failResponse( $req );
	}

	/**
	 * Allow the password to be changed.
	 *
	 * [providerAllowsAuthenticationDataChange description]
	 * @param AuthenticationRequest $req
	 * @param bool $checkData
	 * @return static
	 */
	public function providerAllowsAuthenticationDataChange( AuthenticationRequest $req, $checkData = true ) {
		if ( $req->action === AuthManager::ACTION_REMOVE ) {
			/*
			 * The corresponding credentials should no longer result
			 * in a successful login, but that cannot be implemented
			 * here because there does not appear to be reliable way
			 * to disable an account in WordPress. */
			return \StatusValue::newGood( 'ignored' );
		}

		return \StatusValue::newGood();
	}

	/**
	 * Allow to change propoerties.
	 *
	 * @param array $property
	 * @return bool
	 */
	public function providerAllowsPropertyChange( $property ) {
		return false;
	}

	/**
	 * Set a new password for the user.	The corresponding credentials.
	 *
	 * @param AuthenticationRequest $req
	 * @return static
	 */
	public function providerChangeAuthenticationData( AuthenticationRequest $req ) {
		return AuthenticationResponse::newFail( 'User update is not allowed on the wiki. Pass by the forum.' );
	}

	/**
	 * Return true if user exist in Flarum
	 *
	 * @param string $username
	 * @param string $flags
	 * @return bool
	 */
	public function testUserExists( $username, $flags = User::READ_NORMAL ) {
		return $this->flarumUser->exists();
	}

	/**
	 * MediaWiki auto-creation requires the user to exist in
	 * Flarum. If testUserExists() returns true this should not be
	 * called.
	 *
	 * @param string $user
	 * @param AuthenticationProvider $autocreate
	 * @param array $options
	 * @return static
	 */
	public function testUserForCreation( $user, $autocreate, array $options = [] ) {
		$authFlarumAutoCreate = MediaWikiServices::getInstance()
							->getConfigFactory()
							->makeConfig( 'AuthFlarum' )
							->get( 'AuthFlarumAutoCreate' );
		if ( $autocreate && $authFlarumAutoCreate ) {
			if ( !$this->flarumUser->exists() ) {
				return \StatusValue::newFatal( "No corresponding Flarum user: cannot auto-create" );
			}
			if ( !$this->flarumUser->hasCommentCount() ) {
				return \StatusValue::newFatal( "Not enough post on forum to auto connect on wiki" );
			}

			$user->setEmail( $this->flarumUser->getEmail() );
			$user->setRealName( $this->flarumUser->getUsername() );
		}

		return \StatusValue::newGood();
	}
}
