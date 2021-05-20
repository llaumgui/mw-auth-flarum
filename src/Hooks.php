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

/**
 * Hooks class.
 */
class Hooks {

	/**
	 * Remove change password for preferences.
	 *
	 * @param User $user
	 * @param array &$preferences
	 * @return bool
	 */
	public static function getPreferences( $user, &$preferences ) {
		unset( $preferences['password'] );
		return true;
	}

	/**
	 * Hook for SpecialPage_initList, remove some SpecialPage.
	 *
	 * @param array &$list
	 */
	public static function specialPageInitList( &$list ) {
		unset( $list['ChangeCredentials'] );
		unset( $list['PasswordReset'] );
	}

	/**
	 * Hook for onAuthChangeFormFields. Remove some fields.
	 * @param array $requests
	 * @param array $fieldInfo
	 * @param array &$formDescriptor
	 * @param array $action
	 */
	public static function onAuthChangeFormFields( $requests, $fieldInfo, &$formDescriptor, $action ) {
		unset( $formDescriptor['linkcontainer'] );
		unset( $formDescriptor['passwordReset'] );
		$formDescriptor['authFlarum'] = [
			'type' => 'info',
			'raw' => true,
			'cssclass' => 'mw-form-related-auth-flarum',
			'default' => new \Message( 'authflarum-connection-form-label' ),
			'weight' => 230
		];
	}
}
