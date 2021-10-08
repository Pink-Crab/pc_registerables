<?php

declare(strict_types=1);

/**
 * Base class for all taxonomy tests.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Perique
 */

namespace PinkCrab\Registerables\Tests\Metaboxes;

use Exception;
use PinkCrab\Loader\Hook_Loader;
use Gin0115\WPUnit_Helpers\Objects;
use Gin0115\WPUnit_Helpers\Output;
use Gin0115\WPUnit_Helpers\WP\Meta_Box_Inspector;
use PinkCrab\Perique\Services\View\PHP_Engine;
use PinkCrab\Registerables\MetaBox;
use WP_UnitTestCase;


class Test_Metaboxes extends WP_UnitTestCase {

	/**
	 * Test can add actions to a metabox
	 *
	 * @return void
	 */
	public function test_can_add_actions(): void {
		$metabox = MetaBox::normal( 'test' );
		$metabox->add_action( 'test', function() {} );
		$this->assertNotEmpty( Objects::get_property( $metabox, 'actions' ) );
	}

	/**
	 * Tests that actions added, are added laoder on register()
	 *
	 * @return void
	 */
	public function test_registers_actions(): void {
		$metabox = MetaBox::normal( 'test' );
		$metabox->add_action( 'test', function() {} );

		$loader = new Hook_Loader();
		$metabox->register( $loader );

		// Extract all hooks as an array
		$actions = Objects::get_property( $loader, 'hooks' )->export();

		// Extract our options.
		$extracted_action = array_filter(
			$actions,
			function( $e ) {
				return $e->get_handle() === 'test';
			}
		);

		// Ensure we have our hook
		$this->assertNotEmpty( $extracted_action );
	}

	/**
	 * Tests is_active method, based on screen type.
	 *
	 * @return void
	 */
	public function test_is_active(): void {
		// Set screen to admin dashboard
		set_current_screen( 'dashboard' );

		$metabox = MetaBox::normal( 'test' );
		$metabox->screen( 'post' );

		// test not currently active.
		$this->assertFalse( Objects::invoke_method( $metabox, 'is_active', array() ) );

		// Mock the current screen to edit post.
		set_current_screen( 'edit.php' );
		$screen            = get_current_screen();
		$screen->post_type = 'post';

		// Should now be active.
		$this->assertTrue( Objects::invoke_method( $metabox, 'is_active', array() ) );

		// Set screen to admin dashboard
		set_current_screen( 'dashboard' );
	}

	/**
	 * Test can set a renderable engine and use tempaltes
	 * Example uses php engine, but can be used with Blades etc.
	 *
	 * @return void
	 */
	public function test_can_use_renderable() {
		$metabox = MetaBox::normal( 'renderable' )
			->screen( 'post' )
			->set_renderable( new PHP_Engine( dirname( __DIR__, 1 ) . '/Fixtures/Views/' ) )
			->view_vars( array( 'key' => 'value' ) )
			->render( 'template.php' );

		$loader = new Hook_Loader();
		$metabox->register( $loader );
		$loader->register_hooks();
		do_action( 'add_meta_boxes' );

		// Ensure Metabox is rendered using stub template.(prints title)
		$inspector          = Meta_Box_Inspector::initialise();
		$registered_metabox = $inspector->find( 'renderable' );
		$mock_post_title    = 'TEST';

		$output = Output::buffer(
			function() use ( $inspector, $mock_post_title, $registered_metabox ) {
				$inspector->render_meta_box(
					$registered_metabox,
					\get_post( $this->factory->post->create( array( 'post_title' => $mock_post_title ) ) )
				);
			}
		);
		$this->assertEquals( $mock_post_title, $output );
	}

	/** @testdox It should be possible to check that a metabox is using renderable (template with array data) */
	public function test_has_renderable(): void {
		$metabox = MetaBox::normal( 'renderable' )
			->screen( 'post' )
			->set_renderable( new PHP_Engine( dirname( __DIR__, 1 ) . '/Fixtures/Views/' ) )
			->view_vars( array( 'key' => 'value' ) )
			->render( 'template.php' );

		$this->assertTrue( $metabox->has_renderable() );
	}

	/**
	 * Ensure exception throws if tryign to use render without setitng
	 * a renderable engine.
	 *
	 * @return void
	 */
	public function test_must_set_renderable_to_use_render(): void {
		$this->expectException( Exception::class );
		$metabox = MetaBox::normal( 'renderable' )
			->render( 'template.php' );
	}

	public function test_get_current_screen() {
		 $metabox = MetaBox::normal( 'screen_test' )
			->screen( 'post' );

		global $current_screen;
		$current_screen = null;

		$this->assertNull( Objects::invoke_method( $metabox, 'get_current_screen' ) );
	}

}