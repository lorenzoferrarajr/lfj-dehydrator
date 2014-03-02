lfj-dehydrator
==============
Created By Lorenzo Ferrara Junior

[![Build Status](https://travis-ci.org/lorenzoferrarajr/lfj-dehydrator.png?branch=master)](https://travis-ci.org/lorenzoferrarajr/lfj-dehydrator)

Introduction
---
LfjDehydrator is a tool to speed-up development of data extraction from text using PHP. Please consider that some parts of this project will change.

Installation
---
LfjDehydrator can be installed via `composer` adding `"lorenzoferrarajr/lfj-dehydrator": "dev-master"` to the `composer.json` file or running

    php composer.phar require lorenzoferrarajr/lfj-dehydrator:dev-master

Usage
----
To illustrate the basic usage please refer to the following code:
    
```
<?php

use Lfj\Dehydrator\Dehydrator;
use Lfj\Dehydrator\Content\Content;
use Zend\Uri\Uri;

include "../vendor/autoload.php";

$url = new Uri('http://www.youtube.com/watch?v=tfw0KapQ3qw');
$html = file_get_contents($url->toString());
$content = new Content($html);

$dehydrator = new Dehydrator();

$dehydrator->addPlugin('MyDehydratorPlugin\YouTubeTitlePlugin');

$result = $dehydrator->dehydrate($url, $content)->getResult();

print_r($result);
```

The `Dehydrator` object delegates to plugins the actual data extraction from the content. Plugins are added using the ::addPlugin() method and are instantiated one at a time only when the ::dehydrate() method is called. The `YouTubeTitlePlugin` plugin used in the previous code can be found below.

Plugins
---
A plugin holds the code responsible of extracting data from the content. Each plugin must implement [Lfj\Dehydrator\Plugin\PluginInterface](src/LfjDehydrator/Plugin/PluginInterface.php). Most of the boring code can be omitted extending the [Lfj\Dehydrator\Plugin\AbstractPlugin](src/LfjDehydrator/Plugin/AbstractPlugin.php) class.

The important methods to implement are:

  * `getKey()` must return a string representing the key of the result array holding the data extracted by the plugin
  * `isEnabled()` must return ´true´ or ´false´ depending on if the plugin should be used or not. As an example, one could create a plugin to extract data from a particular website and use `isEnabled()` to check if the current URL is correct or not
  * `run()` should extract the data from the content and populate the result

Normally, the result of two plugins having the same key gets appended to the final result. As an example we can take two plugins having the `::getKey()` method returning `title`. One plugin extracts the text form the `<title>` tag in the `<head>`, the other plugin extracts the text from the `content` attribute of `<meta property="og:title">` tag. The final result of the `Dehydrator` class will be something like:

```php
array(
    'title' => array(
           0 => 'title from title tag',
           1 => 'title from og:title'
    )
)
```

If both the plugins, or just the last one added using `::addPlugin()`, implement the `Lfj\Dehydrator\Plugin\ReplaceablePluginInterface` interface, assuming that the `og:title` related plugin is added least, the result will not get appended but replaced. The final result will be:
    
```php
array(
    'title' => array(
           1 => 'title from og:title'
    )
)
```

or, if the result of the plugin is not an array but a scalar value:

```php
array(
    'title' => 'title from og:title'
)
```


Below, an example plugin that extracts the title of a YouTube video from a YouTube URL:

```php
<?php

namespace MyDehydratorPlugin;

use Lfj\Dehydrator\Plugin\AbstractPlugin;
use Lfj\Dehydrator\Plugin\PluginInterface;
use Lfj\Dehydrator\Plugin\ReplaceablePluginInterface;

class YouTubeTitlePlugin extends AbstractPlugin implements PluginInterface, ReplaceablePluginInterface
{
    public function getKey()
    {
        return 'title';
    }

    public function run()
    {
        $xml = new \DOMDocument();
        $xml->loadHTML($this->getContent()->toString());

        $h1 = $xml->getElementById('watch-headline-title');

        $title = trim(str_replace("\n", '', $h1->nodeValue));

        $this->setResult($title);
    }

    public function isEnabled()
    {
        switch ($this->getUrl()->getHost())
        {
            case 'www.youtube.com':
            case 'youtube.com':
            case 'youtu.be':
                return true;
        }

        return false;
    }
}
```
