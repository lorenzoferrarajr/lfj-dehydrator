<?php

namespace LfjDehydratorTest\HelperClass;

use Lfj\Dehydrator\Plugin\AbstractPlugin;
use Lfj\Dehydrator\Plugin\PluginInterface;

class PluginHelper extends AbstractPlugin implements PluginInterface
{
    public function isEnabled() { return true; }
    public function getKey() { return 'test-plugin'; }
    public function run() {}
    public function getResult() { return array('result'); }
}
