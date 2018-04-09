<?

	$host = "localhost";
	$database = "database";
	$uid = "usedid";
	$passwd = "password";
	
	$collation = "utf8_general_ci";
	$charset = "utf8";

	$dbc=new MySQLi($host,$uid,$passwd,$database);
	if($dbc->connect_errno){
    	echo mysqli_connect_error();
    	exit;
	}

	// Do Database
	$sql = "ALTER DATABASE " . $database . " CHARACTER SET " . $charset . " COLLATE " . $collation . ";";
	echo "<div>" . $sql . "</div>\n\n";
	$dbc->query("SET foreign_key_checks = 0; " . $sql . " SET foreign_key_checks = 1;");
	
	// Do Tables
	$sql = "SELECT concat ( " .
		"'ALTER TABLE ', " .
		"t1.TABLE_SCHEMA, " .
        "'.', " .
        "t1.table_name, " .
        " ' CONVERT TO character set " . $charset . " collate " . $collation . ";'" .
		") " .
        "FROM " . 
    	"information_schema.tables t1 " .  
		"WHERE " .  
    	"t1.TABLE_SCHEMA like '" . $database .  "' AND " .  
    	"t1.TABLE_COLLATION IS NOT NULL AND " . 
    	"t1.TABLE_COLLATION NOT IN ('" . $collation . "'); ";
	//echo "<div>" . $sql . "</div>\n\n";
	$res=$dbc->query($sql) or die($dbc->error);
	while($queries=$res->fetch_array()){
		$sql = "SET foreign_key_checks = 0; " . $queries[0] . " SET foreign_key_checks = 1;";
		$dbc->query($sql);
		echo "<div>" . $sql . "</div>\n\n";
	}

	// Do Columns
	$sql = "SELECT concat ( " .
            "'ALTER TABLE ', " .
                "t1.TABLE_SCHEMA, " .
                "'.', " .
                "t1.table_name, " .
                "' MODIFY ', " .
                "t1.column_name, " .
                "' ', " .
                "t1.data_type, " .
                "'(' , " .
                    "CHARACTER_MAXIMUM_LENGTH, " .
                "')', " .
                "' CHARACTER SET " . $charset . " COLLATE " . $collation . ";' " .
        ") " .
		"FROM " .
    	" information_schema.columns t1 " .
		"WHERE " .
    	" t1.TABLE_SCHEMA like '" . $database .  "' AND " .
    	"t1.COLLATION_NAME IS NOT NULL AND " .
    	"t1.COLLATION_NAME NOT IN ('" . $collation . "');";
 	//echo "<div>" . $sql . "</div>\n\n";   
	$res=$dbc->query($sql) or die($dbc->error);
	while($queries=$res->fetch_array()){
		$sql = "SET foreign_key_checks = 0; " . $queries[0] . " SET foreign_key_checks = 1;";
		$dbc->query($sql);
		echo "<div>" . $sql . "</div>\n\n";
	}

?>