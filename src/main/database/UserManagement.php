<?php

namespace database;

require_once "../util/Objectx.php";
require_once "../util/Stack.php";
require_once "../util/SimpleString.php";

require_once __DIR__ . "/User.php";

use util\Objectx;
use util\Stack;
use util\SimpleString;

use DOMDocument;
use DOMException;

//REM: TODO; Add/Create a function for checking if the user-email registration if 
//REM: TODO; it is already associated with an existing account or is currently in use.
//REM: TODO; And add an editor functionality for superUsers to handle non-superUsers...
final class UserManagement extends Objectx {

    private Stack $users;

    private static string $dataDirPath = '../../../private'; //REM: my gulay... how to use '.env', bruh...
    private static string $dataFileName = 'library-logging-db.xml';
    private string $dataFilePath;
    private DOMDocument $xml;

    private bool $canAsapBackUp;

    public function __construct( bool $canAsapBackup = false ) {
        $this->init( $canAsapBackup );
    }

    private function init( bool $canAsapBackUp ) : void {
        $this->canAsapBackUp = $canAsapBackUp;
        $this->users = new Stack();

        $this->dataFilePath = self::$dataDirPath . '/' . self::$dataFileName;
        $this->xml = new DOMDocument('1.0', 'utf-8');
        $this->xml->formatOutput = true;

        $this->load();
    }

    private function load(): bool {
        if ( file_exists( $this->dataFilePath ) ) {
            try {
                $this->xml->load($this->dataFilePath);
                $xmlUsersData = $this->xml->getElementsByTagName('client');
                if ( $xmlUsersData->length <= 0 )
                    throw new DOMException("xml element tag name 'user' cannot be found...");
                $scanCount = 0;
                foreach ($xmlUsersData as $userData) {
                    $userId = $userData->getElementsByTagName('userId')[0]->getAttribute("value");
                    $userEmail = $userData->getElementsByTagName('userEmail')[0]->getAttribute("value");
                    $userPass = $userData->getElementsByTagName('userPass')[0]->getAttribute("value");
                    $isSuperUser = $userData->getElementsByTagName('isAdmin')[0]->getAttribute("value");
                    $user = new User( $userId, $userPass );
                    $user->setUserEmail( $userEmail );
                    $user->setSuperUser( ($isSuperUser === 'true') ? true : false );

                    //REM: we do better; via HASHING, hashing the targetfile would be good or better...
                    //REM: but for now this will do...
                    if ( $userId && $userEmail && $userPass && $isSuperUser && $user->getUserEmail() != null )
                        $this->users->push( $user );
                    ++$scanCount;
                }
            } catch (DOMException $domE ) {
                trigger_error("Partial fatal: " . $domE->getMessage(), E_USER_WARNING);
                return false;
            } finally {
                // assert(!$this->isEmpty(), "Warning: File existed but, initially EMPTY...");
                if ( $this->isEmpty() || $scanCount !== $this->size() )
                    trigger_error("File existed, but might be corrupted or empty... retrieve: " . $this->size() . " out of " . $scanCount, E_USER_WARNING);
            }
            return true;
        } else
            trigger_error("File does not exist... [WARNING]: Attempting to create a new one before exiting system.", E_USER_WARNING);
        return false;
    }
         //REM: hope~pya, re-assigning... This is included because we haven't figured out how to replace existing data yet.

    private function save(): bool {
        $this->xml = new DOMDocument('1.0', 'utf-8');
        $this->xml->formatOutput = true;
        $root = $this->xml->createElement('clients');
        $root->setAttribute('status', 'updated');
        $this->xml->appendChild($root);
        $users = $this->users->getDataArray();
        foreach ($users as $user) {
            $userElement = $this->xml->createElement( 'client' );
            $userIdElement = $this->xml->createElement( 'userId' );
            $userIdElement->setAttribute( 'value', $user->getUserId() );
            $userEmailElement = $this->xml->createElement('userEmail' );
            $userEmailElement->setAttribute( 'value', $user->getUserEmail() );
            $userPassElement = $this->xml->createElement( 'userPass' );
            $userPassElement->setAttribute( 'value', $user->getUserPass() );
            $isAdminElement = $this->xml->createElement( 'isAdmin' );
            $isAdminElement->setAttribute( 'value', ( $user->isSuperUser() )? 'true': 'false' );

            $userElement->appendChild( $userIdElement );
            $userElement->appendChild( $userEmailElement );
            $userElement->appendChild( $userPassElement );
            $userElement->appendChild( $isAdminElement );
            $root->appendChild($userElement);
        }
        if (!file_exists(self::$dataDirPath)) {
            if (mkdir(self::$dataDirPath, 0755, true))
                trigger_error("Successfully created DIRECTORY for this localize database", E_USER_NOTICE);
            else 
                trigger_error("Something went wrong, upon creating the said DIRECTORY for this localize database", E_USER_ERROR);
        }

        return $this->xml->save($this->dataFilePath, LIBXML_COMPACT ) &&
            ( $this->canAsapBackUp )? $this->createAsapBackUp( $this->xml ) : true;
    }

    
    //REM: Back-up... Make it pretty later. And make it logically correct
    private function createAsapBackUp( ?DOMDocument $domDoc  ) {
        $root = $domDoc->documentElement;
        $root->setAttribute('status', 'backed-up');
        $backUpDir = self::$dataDirPath. "/". "back-up/" ;
        if (!file_exists( $backUpDir )) {
            if (mkdir( $backUpDir, 0755, true))
                trigger_error("Successfully created BACK UP DIRECTORY for this localize database", E_USER_NOTICE);
            else 
                trigger_error("Something went wrong, upon creating the said BACK UP DIRECTORY for this localize database", E_USER_ERROR);
        }
        return $domDoc->save( 
            $backUpDir . self::$dataFileName . "-" . date("d.m.Y-H.i.s") . ".xml", 
            LIBXML_COMPACT | LIBXML_NOEMPTYTAG
        );
    }


    /**
     * @return bool TRUE if user added/created successfully, other-wise, FALSE ~ primarily due to it already existed
     */
    public function add(?User $user): bool {
        if ( $this->searchByUserId( $user->getUserId() ) != null /*|| $this->isExactlyContain($user)*/ )
            return false;
        $newUser = new User( str_replace('-', '', $user->getUserId() ) , $user->getUserPass() ); //REM: Yep, still working on it...
        $newUser->setSuperUser( $user->isSuperUser() );
        $newUser->setUserEmail( $user->getUserEmail() );
        $newUser->setUserPass( password_hash( $newUser->getUserPass(), PASSWORD_ARGON2ID ) ); //REM: Yep, still working on it...
        return $this->users->push($newUser) && $this->save();
    }

    public function isUserIdExisted( ?string $userId ): bool {
        return $this->searchByUserId( $userId ) !== null;
    }

    public function canAsapBackUp(): bool {
        return $this->canAsapBackUp;
    }

    public static function isValidUserId( ?string $userId ): bool {
        return (preg_match( "/^\d{4}-\d{4}$/", $userId ) || preg_match( "/^\d{8}$/", $userId )); //REM: e.q; 1234-5678, 12345678, or 2023-1010
    }

    /**
     * 
     * Is this valid/legal? Best Practice?...
     */
    public function delete( ?string $userId ): ?User {
        $deletedUser = $this->searchByUserId( $userId );
        $this->users->delete($deletedUser);
        $this->save();
        return $deletedUser;
    }

    public function verifyUserId( ?string $userId ): bool {
        return $this->searchByUserId( $userId ) != null;
    }

    public function getByUserId( ?string $userId ): array {
        $user = $this->searchByUserId( $userId );
        if ( !$user )
            return [];
        return [ 
            'userId' => $user->getUserId(),
            'userEmail' => $user->getUserEmail(),
            'userIsAdmin' => $user->isSuperUser()
        ];
    }

    public function searchByUserId( ?string $userId ): ?User {
        if ( !$userId || $this->isEmpty() )
            return null;
        $users = $this->users->getDataArray();
        for ( $i = 0; $i < count($users); ++$i ) {
            if ( $users[$i]->getUserId() === str_replace('-', '', $userId) ) //REM: so mnay ASAP things going on... 
                return $users[$i];
        }
        return null;
    }

    public function verifyUser( string $userId, string $userPass ): bool {
        $userInDb = $this->searchByUserId( $userId );
        if ( !$userInDb )
            return false;
        return password_verify( $userPass, $userInDb->getUserPass() );
    }

    /**
     * 
     * Note: it may not work properly due to the password hashing...
     */
    public function isExactlyContain(?User $targetUser) : bool {
        // if ( !$targetUser || !$targetUser->getUserPass() )
        //     return false;
        // foreach ( $this->users as $user ) {
        //     if ( $user->equals($targetUser) )
        //         return true;
        // }
        // return false;
        return ( $targetUser != null ) && ( $this->users->search( $targetUser ) != null );
    }

    public function isEmpty(): bool {
        return $this->users->isEmpty();
    }

    public function size(): int {
        return $this->users->size();
    }

    /**
     * 
     * @inheritDoc util\Objectx::equals
     */
    public function equals( ?Objectx $obj ): bool {
        if ( !$obj || !( $obj instanceof UserManagement ) || ($obj->size() !== $this->size()) )
            return false;
        if ( $obj === $this )
            return true;
        return $this->users->equals($obj->users);
    }

    /**
     * I haven't learned a more effective way to do this just yet.
     * @inheritDoc util\Objectx::hashCode
     */
    public function hashCode(): int {
        $hash = 17;

        $hash = ( $hash << 31) - ( $hash * 31 ) + $this->size();
        $s = $this->users->getDataArray();
        for ( $i = 0; $i < $this->size(); ++$i)
            $hash = ( $hash << 31) - $hash +  $s[$i]->hashCode();

        return $hash & 0xFFFFFFFF;
    }
    /**
     * 
     * @inheritDoc util\Objectx::toString
     */
    public function toString(): string {
        return "database\UserManagement@". SimpleString::intToHex( $this->hashCode() )."[size=".$this->size()."]";
    }
}       


//REM: ... um, I know it is not a good way of doing things, but more the mean time it serve its purpose.
$usr = new User("user-admin", "123456789");
$usr->setUserEmail("user-admin@basic-logging-system.com");
$usr->setSuperUser( true );

$uM = new UserManagement();
$uM->add( $usr );

?>