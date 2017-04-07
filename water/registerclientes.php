<?php

/*
siempre tener en cuenta "config.inc.php" 
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si el ussuario y la contraseña esta vacia
    //sino muere
    if (empty($_POST['id_cliente']) || empty($_POST['nombre'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor entre ingrese todos los campos";
        
        die(json_encode($response));
    }
    
    //si no hemos muerto (die), nos fijamos si exist en la base de datos
    $query        = " SELECT 1 FROM clientes WHERE id_cliente = :id_cli";
    
    //acutalizamos el :user
    $query_params = array(
        ':id_cli' => $_POST['id_cliente']
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
        $response["message"] = "El ID cliente ya existe";
        die(json_encode($response));
    }
    
    //Si llegamos a este punto, es porque el usuario no existe
    //y lo insertamos (agregamos)
    $query = "INSERT INTO clientes ( id_cliente, nombre, direccion, telefono, e_mail, usuario, contrasena) VALUES ( :id_cli, :nom, :dir, :tel, :e_ma, :usu, :cont) ";
    
    //actualizamos los token
    $query_params = array(
        ':id_cli' => $_POST['id_cliente'],
        ':nom' => $_POST['nombre'],
        ':dir' => $_POST['direccion'],
        ':tel' => $_POST['telefono'],
        ':e_ma' => $_POST['e_mail'],
        ':usu' => $_POST['usuario'],
        ':cont' => $_POST['contrasena']

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
    $response["message"] = "La cliente se guardo correctamente ";
    echo json_encode($response);
    
    //para cas php tu puedes simpelmente redireccionar o morir
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
} else {
?>
 <h1>Register de cliente</h1> 
 <form action="registerclientes.php" method="post"> 
     id cliente:<br /> 
     <input type="text" name="id_cliente" value="" /> 
     <br /><br /> 
     nombre:<br /> 
     <input type="text" name="nombre" value="" /> 
     <br /><br /> 
     Direccion:<br /> 
     <input type="text" name="direccion" value="" /> 
     <br /><br /> 
     telefono:<br /> 
     <input type="text" name="telefono" value="" /> 
     <br /><br /> 
      e_mail:<br /> 
     <input type="text" name="e_mail" value="" /> 
     <br /><br /> 
      Usuario:<br /> 
     <input type="text" name="usuario" value="" /> 
     <br /><br /> 
      contraseña:<br /> 
     <input type="text" name="contrasena" value="" /> 
     <br /><br /> 
     <input type="submit" value="Register New User" /> 
 </form>
 <?php
}

?>