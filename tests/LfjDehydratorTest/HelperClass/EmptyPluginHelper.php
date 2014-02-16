<?php

namespace LfjDehydratorTest\HelperClass;

use Lfj\Dehydrator\Plugin\AbstractPlugin;
use Lfj\Dehydrator\Plugin\PluginInterface;

class EmptyPluginHelper extends AbstractPlugin implements PluginInterface
{
    public function isEnabled() { }
    public function getKey() { }
    public function run() {}
}
