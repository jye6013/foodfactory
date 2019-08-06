<?php
    $hostname = "localhost";
	$db = "jiye";
	
	$conn = new PDO("mysql:host=$hostname;dbname=$db", "root", "");
    
    getOrder();
    
    function getOrder() 
    {
        global $conn;
        
        $cmd = "SELECT * FROM `foodshop` ORDER BY `foodshop`.`orderID` ASC , `foodshop`.`foodName` ASC";
        
        $result = $conn->prepare($cmd);
        $result->execute();
        $count = 0;
        while($row = $result->fetch()) 
        {
            echo "Order #".$row["orderID"]." " .$row["foodName"]
                    ." Quantity: ".$row["quantity"]." Price: $".$row["price"]
                    ." Subtotal: $".$row["subtotal"]."<br>";
            $count += 1;
        }
        if ($count == 0) 
        {
            echo "0 results";
        }
    }
?>
