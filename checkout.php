<?php
    //echo $_GET['order'];
    $json_a = json_decode($_GET['order'], true);
    //javascript
    //var myOrder = JSON.parse($_GET['order']);
    /* foreach ($json_a as $key => $value) {
        if (!is_array($value)) {
            echo $key . '=>' . $value . '<br />';
        } else {
            foreach ($value as $key => $val) {
                if (!is_array($val)) {
                    echo $key . '=>' . $val . '<br />';
                }
                else {
                    foreach ($val as $key2 => $val2) {
                            echo $key2 . '=>' . $val2 . '<br />';
                    }
                }
            }
        }
    } */
     //document.getElementById("demo").innerHTML = myObj.name;
    
    $hostname = "localhost";
	$db = "jiye";
	
	$conn = new PDO("mysql:host=$hostname;dbname=$db", "root", "");
    
    $nextOrderId = getMaxOrderID() + 1;
    //echo "Submited OrderId: ".$nextOrderId."<br>";
    insertOrder($nextOrderId, $json_a);
    
    //try to retrieve it again and display back to the response text 
    //$savedOrder = getOrder($nextOrderId);
    //getOrder($nextOrderId);
    //or just save "successfully save the order"
    
    //function definitions
    function getMaxOrderID()
    {
        global $conn;
        $maxOrderID = 0;
        $cmd = "SELECT Max(`orderID`) as maxOrderId FROM `foodshop`";
        
        $result = $conn->prepare($cmd);
        $result->execute();
        
        //return $result->rowCount();
        
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
    
    //based on this json structure: {"order":[{"name":"tomato","quantity":"2","price":"0.35"}, {...}, ...]}
    function insertOrder($nextOrder, $jsonObj)
    {
        //INSERT INTO `foodshop` (orderID, foodName, quantity, price, subtotal) VALUES (1, 'apple', 3, 0.25, 0.75) 
        global $conn;
        
        $order = $jsonObj['order'];
        //echo "<br>size of order=".sizeof($order);
        foreach ($order as $orderItem)
        {
            $name = $orderItem['name'];
            $quantity = $orderItem['quantity'];
            $price = $orderItem['price'];
            $subtotal = $quantity * $price;
            //echo "<br>name=".$name." "."quantity=".$quantity;
            
            $cmd = "INSERT INTO `foodshop` (orderID, foodName, quantity, price, subtotal) "
                        . " VALUES ($nextOrder, '$name', $quantity, $price, $subtotal)";
            //echo "<br>cmd=".$cmd;
            
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
        
        // output data of each row
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
        //return sasda;
    }
?>