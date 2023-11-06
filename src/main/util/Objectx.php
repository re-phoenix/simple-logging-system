<?php

namespace util;

require_once __DIR__ . "/SimpleReflection.php";
require_once __DIR__ . "/SimpleString.php";

class Objectx implements SimpleReflection {

    private static string $canonicalName = "util\Objectx";
    private static int $hashCode = 0;

    public static function staticInit() {
        if( self::$hashCode <= 0 ) {
            $input = self::$canonicalName;
            self::$hashCode = self::hashString($input);
        }
    }

    /**
     * 
     * @inheritDoc util\SimpleReflection::getCanonicalName
     */
    public function getCanonicalName(): string {
        return self::$canonicalName;
    }

    public function hashCode(): int {
        return self::$hashCode;
    }

    protected static function hashString(string $str): int {
        $hash = 0;
        $len = strlen($str);

        for ($i = 0; $i < $len; ++$i)
            $hash = ($hash << 5) - $hash + ord($str[$i]);

        return $hash & 0xFFFFFFFF;
    }

    public function equals(Objectx $obj): bool {
        return $this === $obj;
    }

    /**
     *  I haven't learned a more effective way to do this just yet.
     */
    public function toString(): string {
        return $this->getCanonicalName() .'@'. SimpleString::intToHex( $this->hashCode() ) . '[]';
    }
};

Objectx::staticInit();

?>