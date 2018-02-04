<?php
namespace Pulp;

use Pulp\DataPipe;
use Pulp\Fs\VirtualFile as vfile;

class Sassphp extends DataPipe { 

	public $parser;
	public $extensionList = ['.scss', '.sass'];

	public function __construct($opts) {
		if (!class_exists('Sass')) {
			throw new \Exception("Sassphp extension not compiled.  Use \Pulp\Scss plugin for native PHP compliation.");
		}
		$this->parser = new \Sass();
		$importPathList = [];
		if (array_key_exists('importPath', $opts)) {
			$importPathList = $opts['importPath'];
		}
		if (array_key_exists('importPathList', $opts)) {
			$importPathList = $opts['importPathList'];
		}
		if (!is_array($importPathList)) {
			$importPathList = array($importPathList);
		}

		$this->parser->setIncludePath( implode(':', $importPathList) );
	}

	protected function _onWrite($data) {
		$cssFile = $data->getPathname();
		$cssFile = str_replace($this->extensionList, '.css', $cssFile);
		$file = new vfile( $cssFile );

		try {
			$file->setContents(
				$this->parser->compile( file_get_contents($data->getPathname()) )
			);
			$this->push($file);
		} catch (\SassException $e) {
//			$this->error("Failed to compile sass");
			throw $e;
		}
	}
}
