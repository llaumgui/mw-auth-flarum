<?php

/*
 * This file is part of the AuthFlarum package.
 *
 * Copyright (C) 2021 Guillaume Kulakowski <guillaume@kulakowski.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use AuthFlarum\FlarumApiService;
use MediaWiki\MediaWikiServices;

/** @phpcs-require-sorted-array */
return [
	'FlarumApiService' => function ( MediaWikiServices $services ) : FlarumApiService {
		return new FlarumApiService();
	}
];
