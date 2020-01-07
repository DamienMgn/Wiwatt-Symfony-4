<?php
namespace App\Service;


class SluggerService
{
    public function slugify($string)
    {
        return preg_replace( '/[^a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*/', '-', strtolower(trim(strip_tags($string))) );
    }
}