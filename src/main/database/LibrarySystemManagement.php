<?php

namespace database;

require_once "../util/Stack.php";

use util\Stack;

class LibrarySystemManagement {

    private Stack $books;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->books = new Stack();
    }

}