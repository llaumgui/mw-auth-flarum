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

use DateTime;
use MediaWiki\MediaWikiServices;

/**
 * FlarumUser class.
 */
class FlarumUser {

	/**
	 * Flarum user Uid
	 * @var int
	 */
	private int $id = 0;
	/**
	 * Flarum user username
	 * @var string
	 */
	private string $username = "";
	/**
	 * Flarum user displayName
	 * @var string
	 */
	private string $displayName = "";
	/**
	 * Flarum user email
	 * @var string
	 */
	private string $email = "";
	/**
	 * Flarum user isEmailConfirmed
	 * @var ?bool
	 */
	private ?bool $isEmailConfirmed = null;
	/**
	 * Flarum user isEmailConfirmed
	 * @var ?DateTime
	 */
	private ?DateTime $joinTime = null;
	/**
	 * Flarum user comment count
	 * @var int
	 */
	private int $commentCount = 0;

	/**
	 * FlarumUser constructor.
	 *
	 * @param string $username Flarum username
	 * @param string $password Flarum password
	 * @return FlarumUser
	 */
	public function __construct( string $username, string $password ) {
		$this->id = MediaWikiServices::getInstance()
									->getService( 'FlarumApiService' )
									->connect( $username, $password );
	}

	/**
	 * Get all flarum user information
	 */
	private function getUserInfo() : void {
		$userInfos = MediaWikiServices::getInstance()
											->getService( 'FlarumApiService' )
											->getUserInfo( $this->id );

		$this->username = $userInfos['username'];
		$this->displayName = $userInfos['displayName'];
		$this->email = $userInfos['email'];
		$this->isEmailConfirmed = $userInfos['isEmailConfirmed'];
		$this->joinTime = new DateTime( $userInfos['joinTime'] );
		$this->commentCount = $userInfos['commentCount'];
	}

	/**
	 * Check if user exists.
	 * @return bool
	 */
	public function exists() : bool {
		if ( $this->id > 0 ) {
			return true;
		}
		return false;
	}

	/**
	 * Flarum Uid getter.
	 *
	 * @return int flarum user Uid
	 */
	public function getId() : int {
		return $this->id;
	}

	/**
	 * Get username
	 * @return string User username
	 */
	public function getUsername() : string {
		if ( $this->username === "" ) {
			$this->getUserInfo();
		}
		return $this->username;
	}

	/**
	 * Get displayName
	 * @return string User displayName
	 */
	public function getDisplayName() : string {
		if ( $this->displayName === "" ) {
			$this->getUserInfo();
		}
		return $this->displayName;
	}

	/**
	 * Get email
	 * @return string User email
	 */
	public function getEmail() : string {
		if ( $this->email === "" ) {
			$this->getUserInfo();
		}
		return $this->email;
	}

	/**
	 * Get isEmailConfirmed
	 * @return string User isEmailConfirmed
	 */
	public function isEmailConfirmed() : bool {
		if ( $this->isEmailConfirmed === null ) {
			$this->getUserInfo();
		}
		return $this->isEmailConfirmed;
	}

	/**
	 * Get joinTime
	 * @return string User joinTime
	 */
	public function getJoinTime() : DateTime {
		if ( $this->joinTime === null ) {
			$this->getUserInfo();
		}
		return $this->joinTime;
	}

	/**
	 * flarum use have needed post ?
	 * @return bool
	 */
	public function hasCommentCount() : string {
		if ( $this->commentCount === 0 ) {
			$this->getUserInfo();
		}

		$needed = MediaWikiServices::getInstance()
							->getConfigFactory()
							->makeConfig( 'AuthFlarum' )
							->get( 'AuthFlarumAutoCreateMinPost' );
		if ( intval( $this->commentCount ) >= intval( $needed ) ) {
			return true;
		}
		return false;
	}
}
