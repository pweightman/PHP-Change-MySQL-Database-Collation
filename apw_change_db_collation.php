<?

	$host = "localhost";
	$database = "database";
	$uid = "usedid";
	$passwd = "password";

	$dbc=new MySQLi($host,$uid,$passwd,$database);
	if($dbc->connect_errno){
    	echo mysqli_connect_error();
    	exit;
	}

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
                "' CHARACTER SET utf8 COLLATE utf8_general_ci;' " .
        ") " .
		"FROM " .
    	" information_schema.columns t1 " .
		"WHERE " .
    	" t1.TABLE_SCHEMA like '" . $database .  "' AND " .
    	"t1.COLLATION_NAME IS NOT NULL AND " .
    	"t1.COLLATION_NAME NOT IN ('utf8_general_ci');";
    
	$res=$dbc->query($sql) or die($dbc->error);
	while($queries=$res->fetch_array()){
		$dbc->query($queries[0]);
		echo "<div>" . $queries[0] . "</div>\n\n";
	}

?>