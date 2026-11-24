<?php

namespace AuthFlarum\Tests;

use AuthFlarum\FlarumApiService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

/** @covers \AuthFlarum\FlarumApiService */
class FlarumApiServiceTest extends TestCase {

	/**
	 * @param Response[] $responses
	 * @param array &$history
	 * @param bool $httpErrors
	 * @return FlarumApiService
	 */
	private function newService( array $responses, array &$history = [], bool $httpErrors = true ): FlarumApiService {
		$handler = HandlerStack::create( new MockHandler( $responses ) );
		$handler->push( Middleware::history( $history ) );
		$client = new Client( [
			'handler' => $handler,
			'http_errors' => $httpErrors,
		] );

		return new FlarumApiService( 'https://forum.example.test/', $client );
	}

	public function testConnectReturnsUserIdAndSendsCredentials(): void {
		$history = [];
		$service = $this->newService( [
			new Response( 200, [], '{"userId":42,"token":"secret-token"}' ),
		], $history );

		$this->assertSame( 42, $service->connect( 'alice', 'correct horse' ) );
		$this->assertCount( 1, $history );

		/** @var RequestInterface $request */
		$request = $history[0]['request'];
		$this->assertSame( 'POST', $request->getMethod() );
		$this->assertSame( 'https://forum.example.test/api/token', (string)$request->getUri() );
		parse_str( (string)$request->getBody(), $formData );
		$this->assertSame( [
			'identification' => 'alice',
			'password' => 'correct horse',
		], $formData );
	}

	public function testConnectSendsTwoFactorToken(): void {
		$history = [];
		$service = $this->newService( [
			new Response( 200, [], '{"userId":7,"token":"secret-token"}' ),
		], $history );

		$this->assertSame( 7, $service->connect( 'alice', 'password', '123456' ) );
		$this->assertStringContainsString(
			'twoFactorToken=123456',
			(string)$history[0]['request']->getBody()
		);
	}

	public function testConnectReturnsZeroOnClientError(): void {
		$service = $this->newService( [ new Response( 401 ) ] );

		$this->assertSame( 0, $service->connect( 'alice', 'invalid' ) );
	}

	public function testConnectReturnsZeroOnServerError(): void {
		$service = $this->newService( [ new Response( 500 ) ] );

		$this->assertSame( 0, $service->connect( 'alice', 'password' ) );
	}

	public function testConnectReturnsZeroOnUnexpectedStatus(): void {
		$history = [];
		$service = $this->newService( [ new Response( 204 ) ], $history, false );

		$this->assertSame( 0, $service->connect( 'alice', 'password' ) );
	}

	public function testGetUserInfoReturnsAttributesAndGroupIds(): void {
		$history = [];
		$service = $this->newService( [
			new Response( 200, [], '{"userId":42,"token":"secret-token"}' ),
			new Response( 200, [], json_encode( [
				'data' => [
					'attributes' => [
						'username' => 'alice',
						'displayName' => 'Alice',
						'email' => 'alice@example.test',
					],
					'relationships' => [
						'groups' => [
							'data' => [
								[ 'type' => 'groups', 'id' => '2' ],
								[ 'type' => 'users', 'id' => '42' ],
								[ 'type' => 'groups', 'id' => '5' ],
							],
						],
					],
				],
			] ) ),
		], $history );
		$service->connect( 'alice', 'password' );

		$this->assertSame( [
			'username' => 'alice',
			'displayName' => 'Alice',
			'email' => 'alice@example.test',
			'userGroups' => [ '2', '5' ],
		], $service->getUserInfo( 42 ) );

		/** @var RequestInterface $request */
		$request = $history[1]['request'];
		$this->assertSame( 'GET', $request->getMethod() );
		$this->assertSame( 'https://forum.example.test/api/users/42', (string)$request->getUri() );
		$this->assertSame( 'Token secret-token', $request->getHeaderLine( 'Authorization' ) );
	}

	public function testGetUserInfoReturnsNullOnUnexpectedStatus(): void {
		$history = [];
		$service = $this->newService( [ new Response( 404 ) ], $history, false );

		$this->assertNull( $service->getUserInfo( 99 ) );
	}
}
