<?php

class Pulp_Sassphp_Test extends \PHPUnit\Framework\TestCase { 

	public function setUp() {
		if (!class_exists('Sass')) {
			$this->markTestSkipped('Extension not loaded');
		}
	}
	public function test_compiler_compiles() {
		$compiler = new \Sass();
		$css = $compiler->compile('
  $color: #abc;
  div { color: lighten($color, 20%); }
	');
		$this->assertGreaterThan( 1, strlen($css));
	}

	public function test_compiler_fails_imports() {
		$compiler = new \Sass();
		$this->expectException('SassException');
		$css = $compiler->compile('
$color: #abc;
div { color: lighten($color, 20%); }
@import "fake.scss";
	');
	}

}

