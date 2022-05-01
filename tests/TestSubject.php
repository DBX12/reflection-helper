<?php

namespace tests;

class TestSubject extends TestSubjectParent
{
    protected $protectedProperty = 'protected value';
    protected static $staticProtectedProperty = 'static protected value';

    protected function protectedFunction(string $arg1): string
    {
        return "Class was called with $arg1";
    }

    protected static function staticProtectedFunction(string $arg1): string {
        return "Static class was called with $arg1";
    }
}
