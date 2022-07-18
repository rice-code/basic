<?php


namespace Tests\Support;


use PHPUnit\Framework\TestCase;
use Tests\Support\Annotation\Cat;

class FillTest extends TestCase
{
    public function testAutoFill()
    {
        $params = [
            'eyes' => 'big eyes',
            'speak' => [
                'language' => 'english'
            ],
            'hair' => [
                'short',
                'long'
            ]
        ];

        $cat = new Cat($params);

        var_dump('eyes: ' . $cat->eyes);
        var_dump($cat->eat);
        var_dump($cat->speak->language);
        var_dump($cat->hair);
    }
}