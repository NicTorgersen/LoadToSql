<?php

class LoadToSql {
    
    public $_db,
            $_filename;

    private $_xml;

    public function __construct (PDO $db, $filename) {
        $this->_db = $db;
        $this->_filename = $filename;
    }

    public function __destruct () {
        $this->_db = null;
        $this->_filename = null;
    }

    public function run ($table) {
        // Set internal errors
        libxml_use_internal_errors(true);
        $this->_xml = simplexml_load_file($this->_filename);
        $entries = [];
        $sql = "
            INSERT INTO " . $table . "
            (code)
            VALUES (:code)
        ";

        if (!$this->_xml) {
            echo 'Failed loading XML:\n';
            foreach(libxml_get_errors() as $error) {
                echo $error->message, '\n';
            }
            return array(
                'error' => libxml_get_errors(),
                'finished' => false
            );
        }

        try {
            $stmt = $this->_db->prepare($sql);
        } catch (PDOException $e) {
            return array(
                'error' => $e,
                'finished' => false
            );
        }

        foreach($this->_xml->body->row as $node) {
            $entries[] = $node["Code"];
        }

        foreach ($entries as $value) {
            $stmt->execute(array(':code' => $value));
        }

        return array(
            'entries' => $entries,
            'finished' => true
        );

        unset($stmt);
    }

}