<?php
namespace Pulp;

use Pulp\DataPipe;
use Pulp\Fs\VirtualFile as vfile;

class Scss extends DataPipe { 

	public $parser;

	public function __construct($opts) {
		$this->parser = new \Leafo\Scss\Compiler();
	}

	protected function _onWrite($data) {
		$cssFile = $data->getPathname();
		$cssFile = str_replace('.less', '.css', $cssFile);
		$file = new vfile( $cssFile );

		$this->parser->parseFile( $data->getPathname() );
		$file->setContents(
			$this->parser->compile()
		);
		$this->push($file);
	}
}
