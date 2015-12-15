<?php
	
	$conn = mysqli_connect('localhost','username','password','database');  
	// Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	$files = scandir("C:/Users/Admin/Desktop/2015/"); //Path to the Main Directory/Folder
	$tableCount = 1; // Counter to check the Total Files processed
	set_time_limit(0); // Sets the Time Limit to Unlimited - saves you from Maximum execution time of 30 seconds exceeded ERROR of PHP
	foreach($files as $file){
		// if the folder contains folders which has the .csv files then use the below IF Statement
			$subfiles = scandir("C:/Users/Admin/Desktop/2015/".$file); //Creates path to the Sub Directory/Folder
			foreach($subfiles as $subfile)
			{
				echo '<br/><br/>';
				$array = explode('.', $subfile);
				$extension = end($array); // gets the extension of the File.
				$tablename = strtolower($array[0]); // gets the Name of the file && Used as tablename
				if($extension == 'csv'){
					// Before Infile Command to be called a Table shall exist
					mysqli_query($conn, 'CREATE TABLE IF NOT EXISTS `'.$tablename.'` (
					`id` INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
					`Ticker` varchar(255) DEFAULT NULL,
					`Date` varchar(10) DEFAULT NULL,
					`Time` time DEFAULT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
					
					$qry = "LOAD DATA LOCAL INFILE 'C:/Users/Admin/Desktop/2015/".$file."/".$subfile."' INTO TABLE `".$tablename ."` FIELDS TERMINATED BY ',' ENCLOSED BY '' LINES TERMINATED BY '\\n' IGNORE 1 LINES (`Ticker`, `Date`, `Time`, `Open`, `High`, `Low`, `Close`, `Volume`, `Open Interest` ) ;";
					//(`Ticker`, @var1, `Time`, `Open`, `High`, `Low`, `Close`, `Volume`, `Open Interest` ) set `Date` = str_to_date(@var1, '%d-%m-%Y')
					
					echo 'Loading ....'.$file."/".$subfile.'<br/>';
					
					if( mysqli_query($conn,$qry) )
					{
						echo '<span style="color: green;">Successfully Data Import for ' . $subfile . ' - Table Count > ' .$tableCount.'</span>- Execution Time taken -> '. mysqli_info($conn) . '<br/>';
						// if(true) exit;  // If you wanna test first with One Table Import then Uncomment this Line
					}
					else{
						echo '<span style="color: red;">Error Occured -- '. mysqli_error($conn) . '</span>';
						// if(true) exit;	// If you wanna test first with One Table Import then Uncomment this Line
					}
					$tableCount++;
				}
			}
		
		}
		mysqli_close($conn);
					
?>										
