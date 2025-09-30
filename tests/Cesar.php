<?php

declare(strict_types = 1);

namespace Freyr\TDD\Tests;

class Cesar
{

   public function encode(string $text, int $shift): string
   {
       $alphabetUpper = range('A', 'Z');
       $alphabetLower = range('a', 'z');
       $splitText = str_split($text);
       $countAlpha = count($alphabetUpper);

       if ($shift < 0) {
           $shift = $countAlpha + $shift;
       }

       $result = '';
       foreach ($splitText as $char) {
           if (ctype_upper($char)) {
               $index = array_search($char, $alphabetUpper, true);
               $result .= $alphabetUpper[($index + $shift) % $countAlpha];
           } elseif (ctype_lower($char)) {
               $index = array_search($char, $alphabetLower, true);
               $result .= $alphabetLower[($index + $shift) % $countAlpha];
           }
       }

       return $result;
   }
}
