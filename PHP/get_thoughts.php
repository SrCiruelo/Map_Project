<?php 
    //SE TIENE QUE ENVIAR LA INFORMACIÓN A TRAVÉS DE LA URL
    //EL NOMBRE HA DE SER Country
    //DEVUELVE UN ARRAY 
    include_once'connection.php';
    $country = $_GET['country'];
    $sql = "SELECT content FROM thoughts WHERE country_id IN(SELECT id FROM country WHERE numcode='$country')";
	$result = $conn->query($sql);

    //ASÍ CREAMOS UN TEXTO CON FORMA DE JSON PERO SIN TENER QUE CODIFICARLO 
    //EL RENDIMIENTO ES MAYOR PESE A QUE TENGAMOS QUE EXCLUIR EL ÚLTIMO ELEMENTO DEL ARRAY
    echo "{\"thoughts\":[";
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo "\"".$row["content"]."\",";
	    }
        echo "\"last_index(Don't Include this element in the array)\"";
	} else {
	    echo "\"last_index(Don't Include this element in the array)\"";
	}
    
    echo "]}";