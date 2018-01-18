<?php

class Pulp_Scss_Test extends \PHPUnit\Framework\TestCase { 

	public function test_compiler_compiles() {
		$compiler = new \Leafo\ScssPhp\Compiler();
		$css = $compiler->compile('
  $color: #abc;
  div { color: lighten($color, 20%); }
	');
		$this->assertGreaterThan( 1, strlen($css));
	}
}

