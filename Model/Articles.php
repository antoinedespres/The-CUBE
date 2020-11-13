<?php

namespace Model;

// The model is defined as a class with many static methods. There are other
// ways to do that, like taking a database as parameter, etc, but this is
// relatively simple while keeping the ability to organize code in various
// methods.
class Articles
{
    public static function get($id)
    {
        return 'babar';
    }
}

