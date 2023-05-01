<?php
declare( strict_types=1 );

namespace martinsluters\WooStoreBinaryBinWidget\Tests\Unit;

use martinsluters\WooStoreBinaryBinWidget\Bootstrap;

/**
 * Unit Tests Related to Bootstrap class
 */
class TestBootstrap extends \WP_UnitTestCase {

	/**
	 * The Bootstrap class needs to exist
	 *
	 * @return void
	 */
	public function testBootstrapClassExists(): void {
		$this->assertTrue( class_exists( Bootstrap::class ) );
	}

	/**
	 * Makes sure that by calling a static method getInstance returns an instance of Bootstrap
	 *
	 * @return void
	 */
	public function testAttemptToGetInstanceMustReturnInstance(): void {
		$this->assertInstanceOf( Bootstrap::class, Bootstrap::get_instance( __FILE__ ) );
	}

	/**
	 * Makes sure that by calling a static method getInstance twice returns the same instance of Bootstrap
	 *
	 * @return void
	 */
	public function testAttemptToGetInstanceTwiceMustReturnSameInstance(): void {
		$this->assertSame( Bootstrap::get_instance( __FILE__ ), Bootstrap::get_instance( __FILE__ ) );
	}

	/**
	 * Make sure that we can null static instance variable.
	 * Method Bootstrap::tearDown is useful to test singleton Bootstrap class.
	 *
	 * @return void
	 */
	public function testTearDownSetsStaticInstanceToNull() {
		$instance = Bootstrap::get_instance( __FILE__ );
		$this->assertSame( Bootstrap::get_instance( __FILE__ ), $instance );

		$instance->tear_down();
		$this->assertNotSame( Bootstrap::get_instance( __FILE__ ), $instance );
	}
}
