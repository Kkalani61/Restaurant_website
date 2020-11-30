<?php
    require "partials/_dbconnect.php";

    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_name = $_SESSION['user_name'];
        $loggedin = true;
    }
    else
    {
        header("Location: Login.php");
    }

    // if($_SERVER['REQUEST_METHOD'] == "POST")
    // {
    //     $isWarning = true;
    //     if(isset($_POST)) {
    //         // var_dump($_POST);
    //         if(count($_POST) != 0) {
    //             $isWarning = false;
    //             $item_ids = array_map('intval', $_POST['checkbox']);
    //             $item_qtys = array_map('intval', $_POST['quantity']);
    //             $item_price = array_map('intval', $_POST['price']);

    //             for($i=0; $i<count($item_ids); $i++)
    //             {
    //                 echo nl2br($item_ids[$i] . "->" . $item_price[$i] . "*" . $item_qtys[$i] . "=" . $item_price[$i]*$item_qtys[$i] . "\n");
    //                 $ord_food_id = $item_ids[$i];
    //                 $ord_quantity = $item_qtys[$i];
    //                 $ord_price = $item_price[$i]*$item_qtys[$i];
    //                 $ord_mode = 1;
    //                 $ord_dest = 2;
                    
    //                 $insert_query = "INSERT INTO FOOD_ORDER VALUES('divesh@gmail.com', $ord_food_id, $ord_quantity, $ord_price, CURDATE(), DEFAULT, DEFAULT, $ord_dest)";
    //                 $order_result = mysqli_query($conn, $insert_query);
    //                 if(!$order_result)
    //                 {
    //                     die("Insert Error " .mysqli_error($conn));
    //                 }
    //                 else
    //                 {
    //                     echo "Order placed successfully";
    //                 }
    //             }
    //         }
    //         // echo nl2br("\n");
    //         // foreach($_POST["checkbox"] as $checkbox)
    //         // {
    //         //     echo $checkbox ." ";
    //         // }
    //     }
    // }

    $sql = "SELECT * FROM ITEM";
    $query_res = mysqli_query($conn, $sql);
    if(!$query_res) {
        die("Query error ".mysqli_connect_error());
    }
    if($query_res)
    {
        // $record = mysqli_fetch_assoc($query_res);
        // while($record) {
        //     echo "{$record['item_id']} {$record['item_name']} {$record['item_price']} <br>";
        //     $record = mysqli_fetch_assoc($query_res);
        // }
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <!-- Jquery -->
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

    <title>Menu</title>
  </head>
  <body>
    <h1> Menu Page </h1>
    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    Launch demo modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade" id="Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm your order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
            </div>
            </div>
        </div>
    </div>

    <form>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col"></th>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $record = mysqli_fetch_assoc($query_res);
                    while($record) {
                        echo "
                            <tr>
                                <th scope=\"row\">
                                    <div class=\"form-check\">
                                        <input class=\"form-check-input checks\" type=\"checkbox\" value='{$record['item_id']}' name=\"checkbox[]\" >
                                    </div>
                                </th>
                                <td id='name{$record['item_id']}'>{$record['item_name']}</td>
                                <td>
                                    <div class=\"form-check\">
                                    <input class=\"form-check-input\" type=\"number\" value='1' min='1' name=\"quantity[]\" id='{$record['item_id']}' disabled>
                                    </div>
                                </td>
                                <td>{$record['item_price']}</td>
                            </tr>";
                        echo "<input type='hidden' name='price[]' value='{$record['item_price']}' id='price{$record['item_id']}' disabled />";

                        $record = mysqli_fetch_assoc($query_res);
                    }
                ?>
            </tbody>
        </table>
        <button id="place" class="btn btn-primary">Place</button>
    </form>

    <?php
        if(isset($isWarning) && $isWarning) {
            echo "<script> alert('Please select item to be placed') </script>";
        }
    ?>
<!------------------------------------------------------------------------------------------------>
    <script>
        var item_ids = [];
        var item_qtys = [];
        var item_prices = [];
        var item_names = [];

        // handle able/disable of qty field
        $(".checks").change(function(){
            //alert("clicked");
            if(this.checked) {
                let quant_ip_id = $(this).val();
                $("#"+quant_ip_id).removeAttr("disabled");
                $("#price"+quant_ip_id).removeAttr("disabled");
                //console.log($(this).val());
            }
            else
            {
                let quant_ip_id = $(this).val();
                $("#"+quant_ip_id).attr("disabled", true);
                $("#price"+quant_ip_id).attr("disabled", true);
            }
        });

        // Handle click on place button
        $('#place').click(function(event) {
            //alert("Clicked");
            event.preventDefault();

            var html = [];
            html.push('<table class="table table-striped">'+
            '<thead>' +
                '<tr>'+
                '<th scope="col">Name</th>'+
                '<th scope="col">Quantity</th>'+
                '<th scope="col">Price</th>'+
                '</tr>'+
            '</thead>'+
            '<tbody>');

            if($("input[name='checkbox[]']:checked").length != 0) 
            {
                $("input[name='checkbox[]']:checked").each(function() {
                    //console.log($(this).val());
                    // get all item details
                    id = $(this).val()
                    item_ids.push(id);
                    item_qtys.push($("#"+id).val());
                    item_prices.push($("#price"+id).val());
                    item_names.push($("#name"+id).text());
                    html.push(
                    '<tr>' +
                    '<td>' + $("#name"+id).text() + '</td>' +
                    '<td>' + $("#"+id).val() + '</td>' +
                    '<td>' + Number($("#price"+id).val()) * Number($("#"+id).val()) + '</td>' +
                    '</tr>'
                    );
                });
                html.push('</tbody></table>');
                // console.log(html);
                // console.log(html.join(''));
                modal_content = html.join('');
                $(".modal-body").html(modal_content);
                $("#Modal").modal('show');
                // console.log(item_ids);
                // console.log(item_qtys);
                // console.log(item_prices);
                // console.log(item_names);
            }
            else
            {
                alert("Please select an item to be orderd");
            }
        });

        // handle confirm order button
        $("#confirm").click(function(event) {
            event.preventDefault();

            console.log(item_ids);
            console.log(item_qtys);
            console.log(item_prices);
            console.log(item_names);

            jQuery.noConflict();
            $.ajax({
                type: "POST",
                url: "PlaceOrder.php",
                data: {
                        'checkbox': item_ids,
                        'quantity': item_qtys,
                        'price': item_prices
                       },
                success: function(data) {
                    data = JSON.parse(data)
                    console.log(typeof data);
                    console.log(data.result);
                    if(data['result']) {
                        alert("Order successful");
                        location.reload(true);
                    }
                }
            });
        });

        // get list of all selected check boxes:
    </script>

        
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
  </body>
</html>