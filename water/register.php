<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si el ussuario y la contraseña esta vacia
    //sino muere
    if (empty($_POST['id_venta1'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor entre el usuairo y el password";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT 1 FROM ventas_restaurante WHERE id_venta1 = :id_ven";
    
    //acutalizamos el :user
    $query_params = array(
        ':id_ven' => $_POST['id_venta1']
    );
    
    //ejecutamos la consulta
    try {
        // estas son las dos consultas que se van a hacer en la bse de datos
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // solo para testing
        //die("Failed to run query: " . $ex->getMessage());
        
        $response["success"] = 0;
        $response["message"] = "Database Error1. Please Try Again!";
        die(json_encode($response));
    }
    
    //buscamos la información
    //como sabemos que el usuario ya existe lo matamos
    $row = $stmt->fetch();
    if ($row) {
        // Solo para testing
        //die("This username is already in use");
        
        $response["success"] = 0;
        $response["message"] = "El rwgistro de venta ya existe";
        die(json_encode($response));
    }
    
    //Si llegamos a este punto, es porque el usuario no existe
    //y lo insertamos (agregamos)
    $query = "INSERT INTO ventas_restaurante ( id_venta1, fecha, cantidad, total, vacios, restaurante) VALUES ( :id_ven, :fec, :cat, :tot, :vac, :rest) ";
    
    //actualizamos los token
    $query_params = array(
        ':id_ven' => $_POST['id_venta1'],
        ':fec' => $_POST['fecha'],
        ':cat' => $_POST['cantidad'],
        ':tot' => $_POST['total'],
        ':vac' => $_POST['vacios'],
        ':rest' => $_POST['restaurante']

    );
    
    //ejecutamos la query y creamos el usuario
    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // solo para testing
        //die("Failed to run query: " . $ex->getMessage());
        
        $response["success"] = 0;
        $response["message"] = "Error base de datos2. Porfavor vuelve a intentarlo";
        die(json_encode($response));
    }
    
    //si hemos llegado a este punto
    //es que el usuario se agregado satisfactoriamente
    $response["success"] = 1;
    $response["message"] = "La venta se guardo correctamente ";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
} else {
?>
 <h1>Registro de Ventas Whater</h1> 
 <form action="register.php" method="post"> 
     id_venta:<br /> 
     <input type="text" name="id_venta1" value="" /> 
     <br /><br /> 
     fecha:<br /> 
     <input type="text" name="fecha" value="" /> 
     <br /><br /> 
     Cantidad<br /> 
     <input type="text" name="cantidad" value="" /> 
     <br /><br /> 
     total:<br /> 
     <input type="text" name="total" value="" /> 
     <br /><br /> 
      vacios:<br /> 
     <input type="text" name="vacios" value="" /> 
     <br /><br /> 
      restaurante:<br /> 
     <input type="text" name="restaurante" value="" /> 
     <br /><br /> 
     <input type="submit" value="Register New User" /> 
 </form>
 <?php
}

?>