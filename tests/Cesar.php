<?php

declare(strict_types = 1);

namespace Freyr\TDD\Tests;

class Cesar
{

   public function encode(string $text, int $shift): string
   {
       $alphabet = range('A', 'Z');
       $splitText = str_split($text);

       $result = '';
       foreach ($splitText as $char) {
           $index = array_search($char, $alphabet, true);
           $result .= $alphabet[$index + $shift];
       }

       return $result;
   }
}
