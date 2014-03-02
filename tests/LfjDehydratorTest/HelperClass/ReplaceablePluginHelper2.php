<?php

namespace LfjDehydratorTest\HelperClass;

use Lfj\Dehydrator\Plugin\AbstractPlugin;
use Lfj\Dehydrator\Plugin\PluginInterface;
use Lfj\Dehydrator\Plugin\ReplaceablePluginInterface;

class ReplaceablePluginHelper2 extends AbstractPlugin implements PluginInterface, ReplaceablePluginInterface
{
    public function isEnabled() { return true; }
    public function getKey() { return 'test-plugin-replaceable'; }
    public function run() {}
    public function getResult() { return array('result'); }
}
