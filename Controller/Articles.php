<?php

namespace Controller;

class Articles
{
    public function get($id)
    {
        $article = \Model\Articles::get($id);
        render('articles', $article);
    }
}
