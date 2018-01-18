<?php
namespace Pulp;

use Pulp\DataPipe;
use Pulp\Fs\VirtualFile as vfile;

class Scss extends DataPipe { 

	public $parser;
	public $extensionList = ['.scss', '.sass'];

	public function __construct($opts) {
		$this->parser = new \Leafo\ScssPhp\Compiler();
		if (array_key_exists('importPath', $opts)) {
			$importPathList = $opts['importPath'];
		}
		if (array_key_exists('importPathList', $opts)) {
			$importPathList = $opts['importPathList'];
		}
		if (!is_array($importPathList)) {
			$importPathList = array($importPathList);
		}

		foreach ($importPathList as $_dir) {
			$this->parser->addImportPath($_dir);
		}
	}

	protected function _onWrite($data) {
		$cssFile = $data->getPathname();
		$cssFile = str_replace($this->extensionList, '.css', $cssFile);
		$file = new vfile( $cssFile );

		$file->setContents(
			$this->parser->compile( file_get_contents($data->getPathname()) )
		);
		$this->push($file);
	}
}
