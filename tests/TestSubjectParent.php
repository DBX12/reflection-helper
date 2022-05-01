<?php

namespace tests;

class TestSubjectParent
{
    protected $parentProperty = 'parentProperty value';
    protected static $staticParentProperty = 'static parentProperty value';

    protected function parentProtectedFunction(string $arg1): string {
        return "Parent was called with $arg1";
    }

    protected static function staticParentProtectedFunction(string $arg1): string {
        return "Static parent was called with $arg1";
    }
}
