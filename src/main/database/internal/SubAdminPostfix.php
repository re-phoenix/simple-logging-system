<?php

//REM: TODO-BE CONTINUE...

namespace database\internal;

require_once "../../util/Objectx.php";

use util\Objectx;
use SplFileObject;

final class SubAdminPostFix extends Objectx {

    private array $dataArray;
    private SplFileObject $file;

    public function __construct( ?string $path ) {
        $this->file = new SplFileObject( $path, 'r' );
    }

}