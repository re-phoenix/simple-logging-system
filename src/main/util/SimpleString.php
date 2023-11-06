<?php

namespace util;

//REM: Not associated with any other custom classes, particularly the custom stdClass named "Objectx."
//REM: Because it could potentially lead to a circular/cyclic dependency or a non-linear hierarchical structure.
//REM: T_T, I regret to say that I haven't yet discovered a solution, but I am actively working towards it...
final class SimpleString {

    //REM: TODO-HERE; more...


    //REM: Caution: All the implementations mentioned here are marked as 'static' for the sole purpose of experimentation.
    public static function intToHex( mixed $int, bool $isPrefix = false ): string {
        if ( !is_int($int) || $int < 0 )
            return null;

        $hexChars = self::toLowerCase('0123456789ABCDEF');
        $hexadecimal = '';

        while ( $int > 0 ) {
            $index = $int % 16;
            $hexadecimal = $hexChars[$index] . $hexadecimal;
            $int = (int)($int / 16);
        }

        if ( $isPrefix )
            $hexadecimal = '0x' . $hexadecimal;

        return $hexadecimal;
    }

    public static function toUpperCase(string $string) {
        $uppercase = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
            if ($char >= 'a' && $char <= 'z')
                $char = chr(ord($char) - 32);
            $uppercase .= $char;
        }
        return $uppercase;
    }    
    
    public static function toLowerCase(string $string) {
        $lowercase = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];
            if ($char >= 'A' && $char <= 'Z')
                $char = chr(ord($char) + 32);
            $lowercase .= $char;
        }
        return $lowercase;
    }

    public static function isBlank( ?string $str ): bool {
        return !$str || empty( trim( $str ) );
    }

}