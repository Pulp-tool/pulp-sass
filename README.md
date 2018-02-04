Pulp-Sass
====

SCSS compiler data pipe for pulp.


Install
===

From your project's .pulp/ folder:


edit composer.json to add new repositories:
```
        {
            "type": "vcs",
            "url": "git@github.com:Pulp-tool/pulp-sass.git"
        }
```

```bash
composer require pulp-tool/pulp-sass
```

From your project's main folder
```bash
composer require twbs/bootstrap:4.0.0
```

Two Drivers
===
This plugin supports either the pure PHP SCSS Compiler from Leafo, or the standard compliant libsass with a PHP extension from absalomedia "sassphp".


Use
===
```php
use \Pulp\LiveReload as lr;                                                                                                                                           
use \Pulp\Scss       as scss;
$p = new \Pulp\Pulp(); 

$watchDirsCode = ['src/**/*.php'];
$watchDirsCss  = ['templates/webapp01/**/*.scss'];
$outputDirCss  =  'templates/webapp01/dist/css/';


$p->task('watch', function() use($p, $watchDirsCode, $watchDirsCss, $outputDirCss) {
    $lr = new lr(); 
    $lr->listen($p->loop);

    $p->watch( $watchDirsCode )->on('change', function($file) use ($p) {
        $p->src($watchDirsCode) 
            ->pipe($lr);
    });       

    $p->watch( $watchDirsCss )->on('change', function($file) use ($p, $outputDirCss) {
        $stream = compileSass($p, $watchDirsCss, $outputDirCss);
        $stream->pipe($lr);
    });   
});   

$p->task('sass', function() use($p, $watchDirsCss, $outputDirCss) {
    $stream = compileSass($p, $watchDirsCss, $outputDirCss);
});   

function compileSass($p, $watchDirsCss, $outputDirCss) {
    return $p->src($watchDirsCss)
        ->pipe(new Pulp\Debug(['verbose'=>true]))
        ->pipe(new scss([
			'importPath'=>'vendor/twbs/bootstrap/scss/'
		]))
        ->pipe(new Pulp\Debug(['verbose'=>true]))
        ->pipe($p->dest($outputDirCss));
};  
```

Make a file like public/templates/webapp01/styles/site.scss

```css
@import "bootstrap.scss"
```
