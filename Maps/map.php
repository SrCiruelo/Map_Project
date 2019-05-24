<html>
<head>
	<script src="https://d3js.org/d3.v5.min.js"></script>
	<script src="https://d3js.org/topojson.v2.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<meta charset="UTF-8">

</head>
<body>
	
<svg width="100%" height="100%">
	<g id="countries"></g>
	<g id="strokes"></g>
</svg>

<select>
 <!--ESTE CÓDIGO COGE DE LA BASE DE DATOS LOS NOMBRES DE LOS PAISES Y LOS INTRODUCE COMO OPCIONES DENTRO DE SELECT-->
<?php
    include_once'../PHP/connection.php';

	$sql = "SELECT name FROM country";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo "<option> " . $row["name"]."</option>";
	    }
	} else {
	    echo "0 results";
	}
	$conn->close();
?> 
</select>

</body>

<!--MOSTRAR MAPA-->
<script type="text/javascript">

	d3.json("../data/world-map.json").then(function(json){
		var svg = d3.select("svg");
		var projection = d3.geoEquirectangular();
		var path = d3.geoPath().projection(projection);

		svg.select("#countries")
		.selectAll("path")
		.data(topojson.feature(json, json.objects.countries).features)
		.enter()
		.append("path")
		.attr("d",path)
		.attr("id",function(d){return "country"+d.id});

		d3.select("#strokes")
		.selectAll("path")
		.data(topojson.feature(json, json.objects.countries).features)
		.enter()
		.append("path")
		.attr("d",path)
		.attr("id",function(d){return "path"+d.id});
	})
</script>
<script>
    //DEBIDO A QUE NO PODEMOS HACER UNA FUNCIÓN QUE DEVUELVA LOS PENSAMIENTOS DIRECTAMENTE PORQUE HAY QUE ESPERAR A QUE NUESTRA HTTPREQUEST FUNCIONE
    //SE CREA UNA VARIABLE GLOBAL Y CADA VEZ QUE SE CARGUEN PENSAMIENTOS NUEVOS SE ASIGNAN EN ESTA VARIABLE
    var current_thoughts = [];
    var get_thoughts = function(country_id){
        var oReq = new XMLHttpRequest(); 
        oReq.onload = function() {
            //current_thoughts = this.responseText;
            current_thoughts = JSON.parse(this.responseText).thoughts;
            //DEBIDO A LIMITACIONES EL ÚLTIMO ELEMENTO DEL ARRAY ES BASURA
            current_thoughts.pop();
            console.log(current_thoughts);
        };
        //SE AÑADE LA INFORMACIÓN EN LA URL DE ESTA FORMA PARA QUE EN EL PHP CON &_GET PODAMOS CONSEGUIR LA INFORMACIÓN
        oReq.open("get", "../PHP/get_thoughts.php?country="+country_id, true);
        oReq.send();
        
    }
get_thoughts("096");
    
</script>
<style type="text/css">

	svg{
		height: 80%;
		width: 80%;
	}
	path:hover {
		fill: grey;
	}

	#countries{
		fill:black;
	}
	#strokes{
		stroke: white;
        fill: none;
	}

</style>

</html>

<!--Map from https://github.com/topojson/world-atlas-->

<!--CREATE TABLE PENSAMIENTOS(
 	country_id  int(11) NOT NULL AUTO_INCREMENT,
    content VARCHAR(280),
    FOREIGN KEY(country_id)
    	REFERENCES country(id)
    
)-->

<!-- PARA PROTOTIPO:
    SELECT CON PAISES //HECHO
    BOTON QUE MUESTRE PENSAMIENTOS DE UN PAIS //FALTA ==> SELECT content FROM thoughts WHERE country_id IN(SELECT id FROM country WHERE name="BRUNEI DARUSSALAM")
    SUBMIT PARA INTRODUCIR PENSAMIENTOS //HECHO
    REDUCIR TODO A FUNCIONES //FALTA
    PONER MAPA EN EL CENTRO //FALTA
    En form.php HAY QUE HACER OTRA QUERY PARA CONSEGUIR EL ID DEL PAIS CON NOMBRE $COUNTRY
-->
    