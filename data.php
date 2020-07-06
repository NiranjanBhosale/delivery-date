<?php

$connection = mysqli_connect('localhost', 'root', '', 'delivery_date');
if($connection)
{
    // echo date("Y-m-d");
}
else{
    echo "Error";
}
?>
<?php
if(isset($_POST['submit']))
{
    $topic = $_POST['topic'];
    $words = $_POST['words'];
    $info = $_POST['info'];
    $current_date = date('Y-m-d');
    $word_count = $words;

    $select_date = "SELECT number_of_words, scheduled_delivery_date FROM products ORDER BY scheduled_delivery_date";
    $result = mysqli_query($connection, $select_date);
    $rowcount=mysqli_num_rows($result);

    if($rowcount >= 1)
    {
        while ($row = mysqli_fetch_assoc($result)) 
        {
            if ($current_date == $row['scheduled_delivery_date'])
            {
                $word_count = $word_count + $row['number_of_words'];
            }
            else if ($current_date != $row['scheduled_delivery_date']) 
            {

                if ($word_count <=1000)
                {
                    $current_date = date('Y-m-d');
                }
                else
                {
                    $current_date = $row['scheduled_delivery_date'];
                    $word_count = $words;
                    $word_count = $word_count + $row['number_of_words'];
                }
            }
        //  echo $word_count;
        }
        if ($word_count <=1000)
        {
            $insert_data = "INSERT INTO products(topic, number_of_words, information, scheduled_delivery_date) VALUES ('$topic', '$words', '$info', '$current_date')";
            $insert_result = mysqli_query($connection, $insert_data);
            // echo "Working!";
        }
        else if ($word_count > 1000) 
        {
            $nextDay = date ('Y-m-d',strtotime('+1 day', strtotime($current_date)));
            $insert_new_date = "INSERT INTO products(topic, number_of_words, information, scheduled_delivery_date) VALUES ('$topic', '$words', '$info', '$nextDay')";
            $insert_result = mysqli_query($connection, $insert_new_date);
            // echo "Working!!";
        }
        // echo "Working";
    }
    else if ($rowcount == 0)
    {
        $insert_new_data = "INSERT INTO products(topic, number_of_words, information, scheduled_delivery_date) VALUES ('$topic', '$words', '$info', '$current_date')";
        $insert_result = mysqli_query($connection, $insert_new_data);
    }
}
?>

<?php
if(isset($_POST['display']))
{
    echo "Hey";
}
?>

<table border="2px">
        <tr>
            <th>Topic</th>
            <th>Number Of Words</th>
            <th>Information</th>
            <th>Schelduled Delivery Date</th>
        </tr>
<?php

     $display = "SELECT * FROM products";
     $disp_res = mysqli_query($connection, $display);
    if($disp_res)
    {
     ?>
     
     
        
            <?php
                while ($rows = mysqli_fetch_assoc($disp_res)) 
                {
                ?>
                <tr>
                <td><?php echo $rows['topic'];?></td>
                <td><?php echo $rows['number_of_words'];?></td>
                <td><?php echo $rows['information'];?></td>
                <td><?php echo $rows['scheduled_delivery_date'];?></td>
                </tr>
                <?php    
                }
            ?>
        
     
     <?php
    }
?>
</table>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
    <label for="">Add a project</label><br>
    <input type="text" name="topic" id="" placeholder="Enter the project topic" required><br><br>
    <input type="text" name="words" id="" placeholder="Enter the no. of words " required><br><br>
    <input type="text" name="info" id="" placeholder="Additional Information"><br><br>
    <input type="submit" name="submit" value="Submit"> <br><br>
    <!-- <button name="display">View</button> -->
    </form>
</body>
</html>