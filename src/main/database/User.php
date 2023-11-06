<?php

namespace database;

require_once "../util/Objectx.php";
require_once "../util/SimpleString.php";

use util\Objectx;
use util\SimpleString;


//REM: final modifiers?
final class User extends Objectx {
    
    private String $userId;
    private String $userPass;
    private ?String $userEmail;
    private bool $isSuperUser;

    public function __construct(String $userId, String $userPass) {
        $this->userId = $userId;
        $this->userPass = $userPass;
        $this->isSuperUser = false;
        $this->userEmail = null;
    }

    public function getUserId(): String {
        return $this->userId;
    }

    public function getUserPass(): String {
        return $this->userPass;
    }

    public function getUserEmail(): ?String {
        return $this->userEmail;
    }

    public function isSuperUser(): bool {
        return $this->isSuperUser;
    }

    public final function setUserPass( ?string $userPass ): void {
        if ( SimpleString::isBlank( $userPass ) )
            return;
        $this->userPass = $userPass;
    }

    public final function setUserEmail( ?string $userEmail ): void {
        if ( SimpleString::isBlank( $userEmail) || !filter_var($userEmail, FILTER_VALIDATE_EMAIL) )
            return;
        $this->userEmail = $userEmail;
    }

    public function setSuperUser( bool $isSuperUser ): void {
        $this->isSuperUser = $isSuperUser;
    }

    /**
     * I haven't learned a more effective way to do this just yet.
     * @inheritDoc util\Objectx::hashCode
     */
    public function hashCode(): int {
        $hash = 17;

        $hash = ( $hash * 31 ) + parent::hashString($this->userId);
        $hash = ( $hash * 31 ) + parent::hashString($this->userPass);

        return $hash & 0xFFFFFFFF; //REM: constraint into 32bit (4 bytes)
    }

    /**
     * @inheritDoc util\Objectx::equals
     */
    public function equals(?Objectx $user): bool {
        if (!$user || !($user instanceof User))
            return false;
        if ($user === $this)
            return true;
        return $user->userId === $this->userId 
                && $user->userPass === $this->userPass
                && $user->userEmail === $this->userEmail;
    }

    public function toString(): string {
        return "database\User@". SimpleString::intToHex( $this->hashCode() ) ."[userName=\"".$this->userId."\"]";
    }
}


?>