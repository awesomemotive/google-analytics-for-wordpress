<?php

class Yoast_GA_Admin_Menu_Test extends GA_UnitTestCase {


	/**
	 * Regression test for https://www.pivotaltracker.com/story/show/87934306
	 *
	 * We want to make sure the slugs for the admin submenu pages are always the same, independent to the way they are labeled.
	 * The submenu labels are influenced by the language that has been selected. The slugs should not...
	 *
	 * @covers Yoast_GA_Admin_Menu::create_admin_menu
	 */
	public function test_submenu_slugs() {
		new Yoast_GA_Admin();
		$this->go_to( get_admin_url( '/' ) );

		do_action('plugins_loaded');
		do_action('admin_menu');

		global $_wp_submenu_nopriv;

		$ga_admin_submenu_items = $_wp_submenu_nopriv['yst_ga_dashboard'];

		$ga_admin_submenu_slugs = array_keys( $ga_admin_submenu_items );

		$expected = array(
			'yst_ga_dashboard',
			'yst_ga_settings',
			'yst_ga_extensions',
		);

		$this->assertEquals( $expected, $ga_admin_submenu_slugs );
	}
}
