<?php

namespace LfjDehydratorTest\HelperClass;

use Lfj\Dehydrator\Plugin\AbstractPlugin;
use Lfj\Dehydrator\Plugin\PluginInterface;
use Lfj\Dehydrator\Plugin\ReplaceablePluginInterface;

class EmptyReplaceablePluginHelper2 extends AbstractPlugin implements PluginInterface, ReplaceablePluginInterface
{
    public function isEnabled() { }
    public function getKey() { }
    public function run() {}
    public function getResult() { }
}
