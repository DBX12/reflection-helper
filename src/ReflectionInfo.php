<?php

namespace dbx12\reflectionHelper;

/**
 * Data container for information about a reflection
 */
class ReflectionInfo
{
    /** @var string */
    public $className;
    /** @var object|null */
    public $object;
    /** @var \ReflectionClass */
    public $reflection;
}
