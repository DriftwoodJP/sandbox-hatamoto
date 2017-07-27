<?php
/**
 * Class TwitterShortcodeTest
 *
 * @package Sandbox_Hatamoto
 */

class TwitterShortcodeTest extends WP_UnitTestCase {
	function test_twitter_shortcode() {
		$html = do_shortcode( '[twitter]@driftwoodjp[/twitter]' );
		$this->assertEquals( '<a href="https://twitter.com/driftwoodjp">@driftwoodjp</a>', $html );
		// without @
		$html = do_shortcode( '[twitter]driftwoodjp[/twitter]' );
		$this->assertEquals( '<a href="https://twitter.com/driftwoodjp">@driftwoodjp</a>', $html );
	}
}
