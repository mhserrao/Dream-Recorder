<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Maizy's Form!!! - FINALLY</title>
        <style media="screen">
            body {
                background-color:#333333;
                padding:40px;
                color:white;
                font-family: verdana;
                font-size:25px;
                text-align: center;
            }
            table {
                position: absolute;
                align: center;
                padding: 15px;
                border-collapse: collapse;
                width: 50%;
            }
            table, th, td {
                border: 5px solid black;
                padding: 15px;
                background-color:#1a75ff;
            }
            #message{
                align-self: right;
                width: 20%;
                font-size: 100px;
                float: right;
                position: relative;
                right: 150px;
                top: 100px;
                padding: 15px;
                height: 20%;
            }
            #title{
                font-size:50px;
                font-weight: bold;
            }
        </style>
        <script src=ajax.js></script>
        <script type="text/javascript">
            function remove (id) {
                console.log(id);

                var client = new HttpClient();
                client.requestType = "POST";

                var string = `command=delete&id=${encodeURI(id)}`;
                client.makeRequest('dream.php', string);

                client.callback = function (result) {
                    if (result.includes("THE TABLE")) {
                        var answer= "YAY";
                        console.log("YEEEES");
                        popup(answer, "#ff66cc"); //pink
                    } else {
                        var answer = "NOOOOO";
                        popup(answer, "#00e64d"); //green
                    }
                }

                var row = document.getElementById(id);
                row.style.display="none";
            };

            function popup(words, color) {
                message.innerHTML = words;
                message.style.display = "block";
                message.style.backgroundColor = color;
                document.addEventListener("mousedown", pop);
                function pop(e) {
                    message.style.display = "none";
                    document.removeEventListener("mousedown", pop);
                };
            }
        </script>
    </head>
    <body>
        <?php
        $hostname = "localhost";
        $username = "serramai";
        $password = 'S@$t6BUhf7r';
        $databasename = "serramai_wp_assignment";
        $mysqli = new mysqli($hostname, $username, $password, $databasename);

        if ($mysqli->connect_error) {
            die("connection filed".$mysqli->connect_error);
        }

        $command = $_REQUEST['command'];
        $id = $_REQUEST['id'];

        if($command == "delete"){
          $results = $mysqli->query("DELETE FROM nighttime WHERE id=".$id);
        } else {
          $dream = isset($_POST["dream"]) ? $_POST["dream"] : "";
          $type = isset($_POST["type"]) ? $_POST["type"] : "";

          $query = "INSERT INTO nighttime (id, dream, type) VALUES (NULL, ?, ?)";

          $stmt = $mysqli->stmt_init();
          if ($stmt->prepare($query)) {
              $stmt->bind_param("ss", $dream, $type);
              $stmt->execute();

              echo "<div id='title'>";
              echo "THE TABLE";
              echo "</div>";
          } else {
              echo "don't got it";
          }
          $stmt->close();
      }

        $query = "SELECT id, dream, type FROM nighttime ORDER BY type LIMIT 20";

        $stmt = $mysqli->stmt_init();
        if ($stmt->prepare($query)) {
        	$stmt->execute();
            $stmt->bind_result($tempId, $tempDream, $tempType);
        	echo "<br><br><table>
        			<tr><th>DREAM</th><th>TYPE</th><th>ID</th><th>DELETE</th></tr>";
        	while ($stmt->fetch()) {
        		echo "<tr id=".$tempId.">
        				<td>".$tempDream."</td>
        				<td>".$tempType."</td>
                        <td>".$tempId."</td>
                        <td><button id='button' onclick=remove('".$tempId."')>X</button></td>
                        </tr>\n";
        	}
        	echo "</table>";
        	$stmt->close();
        } else {
        	$error = "Sorry could not retrieve scores";  echo $error;  return;
        }
        ?>

        <div id="message"></div>

    </body>
</html>
