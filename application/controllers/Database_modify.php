 <?php defined('BASEPATH') OR exit('No direct script access allowed');

class Database_modify{

	// Function to create the tables and fill them with the default data
    function deleteAndCreateDatabase()
    {

    	$database="inventory_system";

        // Connect to the database
        $mysqli = mysqli_connect("localhost", "root", "", $database);

        // Check for errors
        if (mysqli_connect_errno()){
            echo "mysqli not connect";
        }
        
		// $deletedatabase=mysqli_query($mysqli,"DROP DATABASE IF EXISTS `$database`");

		/* query all tables */
		$sql = "SHOW TABLES WHERE tables_in_inventory_system not like 'ci_%'";
		if($result = mysqli_query($mysqli,$sql)){
		  /* add table name to array */
		  while($row = mysqli_fetch_row($result)){
		    $found_tables[]=$row[0];
		  }
		}
		else{
		  die("Error, could not list tables.");
		}

		/* loop through and drop each table */
		foreach($found_tables as $table_name){
		  $sql = "DROP TABLE $database.$table_name";
		  if($result = mysqli_query($mysqli,$sql)){
		    // echo "Success - table $table_name deleted.<br>";
		  }
		  else{
		    // echo "Error deleting";
		  }
		}

		// Open the default SQL file
        $query = file_get_contents('install/sql/install.sql');

        // Execute a multi query
        $multi_query = $mysqli->multi_query($query);

       	// Close the connection
        $mysqli->close();

        if ($multi_query){
        	// echo "Database successfully updated";
        } else {
             // echo "Database not created";
        }
    }
}
?>


 