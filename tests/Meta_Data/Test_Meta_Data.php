<?php

declare(strict_types=1);

/**
 * Base class for all taxonomy tests.
 *
 * @since 0.4.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Core
 */

namespace PinkCrab\Registerables\Tests\Metaboxes;

use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Loader\Loader;
use PinkCrab\Registerables\Meta_Data;
use WP_UnitTestCase;


class Test_Meta_Data extends WP_UnitTestCase {

	/**@testdox It should be possible to create a new meta data item with its meta key. */
	public function test_can_create_meta(): void {
		$meta = new Meta_Data( 'meta_key' );
		$this->assertEquals( 'meta_key', $meta->get_meta_key() );
	}

	/** @testdox It should be possible to set the meta type. */
	public function test_can_set_meta_type() {
		$meta = new Meta_Data( 'meta_key' );
		$meta->meta_type( 'user' );

		$this->assertEquals( 'user', Objects::get_property( $meta, 'meta_type' ) );
	}

	/** @testdox It should be possible to the data type for the meta data. */
	public function test_can_set_object_type(): void {

		$meta = new Meta_Data( 'meta_key' );
		$meta->type( 'string' );

		$this->assertEquals( 'string', $meta->parse_args()['type'] );
	}

	/** @testdox It should be possible to the description for the meta data. */
	public function test_can_set_description(): void {

		$meta = new Meta_Data( 'meta_key' );
		$meta->description( 'string' );

		$this->assertEquals( 'string', $meta->parse_args()['description'] );
	}

	/** @testdox It should be possible to the single for the meta data. */
	public function test_can_set_single(): void {

		$meta = new Meta_Data( 'meta_key' );
		$meta->single();

		$this->assertTrue( $meta->parse_args()['single'] );
	}

	/** @testdox It should be possible to the default for the meta data. */
	public function test_can_set_default(): void {

		$meta = new Meta_Data( 'meta_key' );
		$meta->default( 'string' );

		$this->assertEquals( 'string', $meta->parse_args()['default'] );
	}

	/** @testdox It should be possible to the rest_schema for the meta data. */
	public function test_can_set_rest_schema(): void {

		$meta = new Meta_Data( 'meta_key' );
		$meta->rest_schema( 'string' );

		$this->assertEquals( 'string', $meta->parse_args()['show_in_rest'] );
	}

	/** @testdox It should be possible to set the meta subtype when needed. */
	public function test_can_set_subtype() {
		$user_meta = new Meta_Data( 'user_meta' );
		$user_meta->meta_type( 'user' );
		$this->assertArrayNotHasKey( 'object_subtype', $user_meta->parse_args() );

		$post_meta = new Meta_Data( 'post_meta' );
		$post_meta->meta_type( 'post' );
		$post_meta->object_subtype( 'page' );

		$this->assertEquals( 'page', $post_meta->parse_args()['object_subtype'] );
	}

	/** @testdox It should be possible to set the callable used to for the sanitization of the data to be set. */
	public function test_can_set_sanitize_callable(): void {
		$meta = new Meta_Data( 'meta_key' );
		$meta->sanitize( 'strtoupper' );

		$callable = $meta->parse_args()['sanitize_callback'];
		$this->assertEquals( 'UPPER', $callable( 'upper' ) );
	}

	/** @testdox It should be possible to set the callable used to for the sanitization of the data to be set. */
	public function test_can_set_permissions_callable(): void {
		$meta = new Meta_Data( 'meta_key' );
		$meta->permissions( 'is_string' );

		$callable = $meta->parse_args()['auth_callback'];
		$this->assertTrue( $callable( 'upper' ) );
		$this->assertFalse( $callable( 123 ) );
	}
}