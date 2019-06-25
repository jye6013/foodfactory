<?php
    $json_a = json_decode($_GET['order'], true);
    $hostname = "localhost";
	$db = "jiye";
	
	$conn = new PDO("mysql:host=$hostname;dbname=$db", "root", "");
    
    $nextOrderId = getMaxOrderID() + 1;
    insertOrder($nextOrderId, $json_a);
  
    function getMaxOrderID()
    {
        global $conn;
        $maxOrderID = 0;
        $cmd = "SELECT Max(`orderID`) as maxOrderId FROM `foodshop`";
        
        $result = $conn->prepare($cmd);
        $result->execute();
       
        if ($result->rowCount() > 0)
		{
			$row = $result->fetch();
            $maxOrderID = $row['maxOrderId'];
            if ($maxOrderID == null) 
            {
                $maxOrderID = 0;
            }
        }
            
        return $maxOrderID;
    }
    
    function insertOrder($nextOrder, $jsonObj)
    {
        global $conn;
        
        $order = $jsonObj['order'];
        foreach ($order as $orderItem)
        {
            $name = $orderItem['name'];
            $quantity = $orderItem['quantity'];
            $price = $orderItem['price'];
            $subtotal = $quantity * $price;
            $cmd = "INSERT INTO `foodshop` (orderID, foodName, quantity, price, subtotal) "
                        . " VALUES ($nextOrder, '$name', $quantity, $price, $subtotal)";
            $result = $conn->prepare($cmd);
            $result->execute();
        }
    }
    
    function getOrder($orderId) 
    {
        global $conn;
        
        $cmd = "SELECT orderID, foodName, quantity, price, subtotal FROM `foodshop` WHERE `orderID` = $orderId";
        
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
            //echo "0 results";
        }
    }
?>
