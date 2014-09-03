<?php
    
    require_once('loadtosql.class.php');

    $db = '';
    $class = '';
    $nodes = [];

    try {
        // 
        // PDO("sqlsrv:server=[sqlservername];Database=[sqlserverdbname]",  "[username]", "[password]")
        $db = new PDO('sqlite:test_db.sqlite3');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Connection error:\n';
        echo $e->getMessage();
    }
    
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS test (
                id INTEGER PRIMARY KEY,
                code TEXT,
                value TEXT,
                dataareaid TEXT
            )
        ");
    } catch (PDOException $e) {
        echo 'Execution error:\n';
        echo $e->getMessage();
    }

    $class = new LoadToSql($db, 'T0137.xml');
    $values = $class->run('test');
    /*foreach ($values as $value) {
        echo $value[1];
    }*/