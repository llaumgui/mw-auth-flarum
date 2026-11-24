<?php

namespace AuthFlarum\Tests;

use AuthFlarum\Hooks;
use PHPUnit\Framework\TestCase;

/** @covers \AuthFlarum\Hooks */
class HooksTest extends TestCase {

	public function testGetPreferencesRemovesPasswordPreference(): void {
		$preferences = [
			'language' => [ 'type' => 'select' ],
			'password' => [ 'type' => 'password' ],
		];

		$this->assertTrue( Hooks::getPreferences( null, $preferences ) );
		$this->assertSame( [ 'language' => [ 'type' => 'select' ] ], $preferences );
	}

	public function testSpecialPageInitListRemovesCredentialPages(): void {
		$specialPages = [
			'ChangeCredentials' => 'change-credentials-handler',
			'PasswordReset' => 'password-reset-handler',
			'UserLogin' => 'login-handler',
		];

		Hooks::specialPageInitList( $specialPages );

		$this->assertSame( [ 'UserLogin' => 'login-handler' ], $specialPages );
	}

	public function testOnAuthChangeFormFieldsReplacesCredentialLinksWithNotice(): void {
		$formDescriptor = [
			'linkcontainer' => [ 'type' => 'info' ],
			'passwordReset' => [ 'type' => 'submit' ],
			'username' => [ 'type' => 'string' ],
		];

		Hooks::onAuthChangeFormFields( [], [], $formDescriptor, 'login' );

		$this->assertArrayNotHasKey( 'linkcontainer', $formDescriptor );
		$this->assertArrayNotHasKey( 'passwordReset', $formDescriptor );
		$this->assertSame( [ 'type' => 'string' ], $formDescriptor['username'] );
		$this->assertSame( 'info', $formDescriptor['authFlarum']['type'] );
		$this->assertTrue( $formDescriptor['authFlarum']['raw'] );
		$this->assertSame(
			'warningbox mw-form-related-auth-flarum',
			$formDescriptor['authFlarum']['cssclass']
		);
		$this->assertInstanceOf( \Message::class, $formDescriptor['authFlarum']['default'] );
		$this->assertSame( 230, $formDescriptor['authFlarum']['weight'] );
	}
}
