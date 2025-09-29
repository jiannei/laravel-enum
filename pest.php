<?php

/*
 * This file is part of the Jiannei/laravel-enum.
 *
 * (c) Jiannei <jiannei@sinan.fun>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/*
|--------------------------------------------------------------------------
| Test Suites
|--------------------------------------------------------------------------
|
| Here you may define test suites for your application. By default, Pest
| will use the "tests" directory to discover your test files. However,
| you may freely adjust this behavior to your needs.
|
*/

/*
|--------------------------------------------------------------------------
| Coverage
|--------------------------------------------------------------------------
|
| Here you may configure the coverage settings for your test suite. By
| default, Pest will use the coverage settings from your PHPUnit
| configuration file.
|
*/

covers('Jiannei\Enum\Laravel\Support\Traits\EnumEnhance');
covers('Jiannei\Enum\Laravel\Support\Enums\HttpStatusCode');
