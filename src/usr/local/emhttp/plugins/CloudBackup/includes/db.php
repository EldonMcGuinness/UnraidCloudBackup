<?php

# Used when working with the DB
class DB extends SQLite3 {

    private $logFile;

    public function __construct( $dbname, $logFile ){
        $this->open( $dbname );
        $this->createTable();
        $this->logFile = $logFile;
    }

    # Helper function to create the table if needed
    private function createTable(){
        $this->exec("CREATE TABLE IF NOT EXISTS 'oAuth' ( id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT UNIQUE, provider TEXT, token TEXT );");
        $this->exec("CREATE UNIQUE INDEX IF NOT EXISTS ux_oAuth_name ON oAuth(name);");

    }

    public function updateToken( $name, $token ){
        try {
            $this->exec("UPDATE 'oAuth' SET token = '" . $token . "' WHERE name = '" . $name . "';");
            //$this->logger("update result: " . $this->changes());
            return ($this->changes() > 0);

        } catch(e) {
            return false;

        }
        
    }

    public function addToken($name, $provider, $token){
        try {
            $this->exec("INSERT INTO 'oAuth' ('name', 'provider', 'token') VALUES ('" . $name . "', '" . $provider . "', '" . $token . "');");
            //$this->logger("insert result: " . $this->changes());
            return ($this->changes() > 0);

        } catch(e) {    
            return false;

        }
    }

    public function upsertToken($name, $provider, $token){
        if ( $this->addToken($name, $provider, $token) ){
            return true;

        }else{
            return $this->updateToken($name, $token);

        }

    }

    public function getToken( $name ){
        $this->exec("SELECT * FROM 'oAuth' WHERE name = " . $name . ";");

    }

    public function getTokens(){
        $results = $this->query("SELECT * FROM 'oAuth';");
        
        $rows = [];

        while ($row = $results->fetchArray(SQLITE3_ASSOC)){
            $rows[] = $row;

        }
        
        return $rows;

    }

    public function delToken( $id ){
        $this->exec("DELETE FROM 'oAuth' WHERE 'id' == " . $id . ";");    

    }
    

    private function logger($cmd){
        fwrite($this->logFile, $cmd);
    }

}

?>