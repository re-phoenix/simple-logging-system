<?php

namespace util;


require_once __DIR__ . "/Objectx.php";
require_once __DIR__ . "/SimpleString.php";


class Stack extends Objectx 
{

    private static string $canonicalName = "util\Stack";
    
    private array $dataArray;   
    private int $size;

    /**
     * 
     * @inheritDoc util\Objectx::getCanonicalName
     */
    public function getCanonicalName(): string {
        return self::$canonicalName;
    }

    /**
     * I haven't learned a more effective way to do this just yet.
     * @inheritDoc util\Objectx::hashCode
     */
    public function hashCode(): int {
        $hash = 17; //REM: Initial prime number

        //REM: Combine hash with the canonical name
        $hash = ($hash * 31) + parent::hashString(Stack::$canonicalName);

        //REM:  Combine hash with the size of the Stack;
        $hash = ($hash * 31) + $this->size;

        //REM: Combine hash with the elements of the Stack
        foreach ($this->dataArray as $element)
            $hash = ($hash * 31) + parent::hashString(serialize($element));

        return $hash & 0xFFFFFFFF;
    }

    public function __construct()
    {
        $this->dataArray = [];
        $this->size = 0;
    }

    public function push( ?Objectx $item ): bool
    {
        if ( !$item )
            return false;
        //REM: TODO-HERE; do more robust logic/system;

        $this->dataArray[$this->size++] = $item;
        return true;
    }

    public function pop(): ?Objectx
    {
        if ($this->isEmpty())
            return null;

        //REM: It wasn't deleted; instead, it was temporarily concealed, awaiting eventual overwriting.
        $removeItem = $this->dataArray[--$this->size];
        return $removeItem;
    }

    public function peek(): ?Objectx
    {
        if ($this->isEmpty())
            return null;
        return ( $this->dataArray[$this->size - 1] );
    }

    public function size(): int
    {
        return $this->size;
    }

    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    /**
     * 
     * Note: Please be aware that this is not included in the typical Stack method.
     */
    public function delete( ?Objectx $itemToRemove ): array {
        $deletedItems = [];
        for ($i = 0; $i < $this->size; $i++) {
            $item = array_shift($this->dataArray);
            if ($item !== $itemToRemove) 
                array_push($this->dataArray, $item);
            else {
                $deletedItems[] = $item;
                --$this->size;
            }
        }
    
        return $deletedItems;
    }

    //REM: Oh my gulay, passing by ref ba ito?...
    //REM: It is imperative to implement a deep copy or cloning mechanism
    //REM: But for this implemenation, ...it is for educational pursuit...
    public function getDataArray(): array {
        return $this->dataArray;
    }

    private function searchIndexOf( ?Objectx $item ): int
    {
        if ( $item != null ) {
            for ($i = 0; $i < $this->size; ++$i) {
                if ($this->dataArray[$i]->equals($item))
                    return $i;
            }
        }
        return -1;
    }

    public function search( ?Objectx $item ): ?Objectx
    {
        if ($item == null || !( $this->isExactlyContain( $item ) ) )
            return null;
        return $item;
    }

    public function isExactlyContain( ?Objectx $item ): bool 
    {
        return ($this->searchIndexOf($item) != -1);
    }

    /**
     * 
     * @inheritDoc util\Objectx::equals
     */
    public function equals( ?Objectx $obj ): bool {
        if ( !$obj || !( $obj instanceof Stack ) || ($obj->size() !== $this->size()) )
            return false;
        if ( $obj === $this )
            return true;
        for ( $i = 0; $i < $this->size(); ++$i ) {
            if ( !$this->dataArray[$i]->equals( $obj->dataArray[$i] ) )
                return false;
        }
        return true;
    }

    /**
     * @inheritDoc util::Objectx::toString
     */
    public function toString(): string {
        return $this->getCanonicalName().'@'. SimpleString::intToHex($this->hashCode()). '[size='.$this->size().']';
    }
}


