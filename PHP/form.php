<?php

    function get_ip(){
        //COMPRABAR QUE PODEMOS CONSEGUIR LA IP DE ESTA FORMA
        //isset comprueba si es distinto de NULLL
        //si se usa este método o el siguiente el usuario puede cambiar la respuesta a lo que quiera 
        //Esto no puede ejecutar sql injection pero si querys no válidas
        if(isset($_SERVER['HTTP_CLIENT_IP'])){
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
            return(isset($_SERVER['REMOTE_ADDR'])?
                $_SERVER['REMOTE_ADDR']:'');
        }
    }
    //incluye la conexión a la base de datos
    include_once'connection.php';
    $sql = "INSERT INTO thoughts(country_id,content) VALUES((SELECT id FROM country WHERE name=?),?)";
    //Preparamos el statement
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        echo "SQL statements failed";
    }
    else{
        //Si se ha conseguido preparar el statement ejecutamos el código
        $ip = get_ip();
        $country;
        //se usa la api ip-api que necesita como parámetro la ip y devuelve un json con la información que se puede obtener de la ip del usuario
        $query=  json_decode(file_get_contents('http://ip-api.com/json/'.$ip));
        if($query && $query->{'status'} == 'succes' ){
            $country =  $query->{'country'};
        }
        else{
            $country = "0";
        }
        $country= "BRUNEI DARUSSALAM";
        mysqli_stmt_bind_param($stmt,"ss",$country,$_POST['content']);
        mysqli_stmt_execute($stmt);
        
    }
    

    header('Location: ../Maps/map.php');
