<?php

namespace Controller;

class Home
{
    public function get()
    {
        render('home/home', []);
    }
}
