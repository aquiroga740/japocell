<?php
	require_once "main.php";

	/*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['cliente_id']);


    /*== Verificando categoria ==*/
	$check_clientes=conexion();
	$check_clientes=$check_clientes->query("SELECT * FROM clientes WHERE cliente_id='$id'");

    if($check_clientes->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                el cliente no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_clientes->fetch();
    }
    $check_clientes=null;

    /*== Almacenando datos ==*/
    $nombre=limpiar_cadena($_POST['cliente_nombre']);
    $telefono=limpiar_cadena($_POST['cliente_telefono']);


    /*== Verificando campos obligatorios ==*/
    if($nombre==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if($telefono!=""){
    	if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}",$telefono)){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                El TELEFONO no coincide con el formato solicitado
	            </div>
	        ';
	        exit();
	    }
    }


    /*== Verificando nombre ==*/
    if($nombre!=$datos['cLIENTE_nombre']){
	    $check_nombre=conexion();
	    $check_nombre=$check_nombre->query("SELECT cliente_nombre FROM clientes WHERE cliente_nombre='$nombre'");
	    if($check_nombre->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
	            </div>
	        ';
	        exit();
	    }
	    $check_nombre=null;
    }


    /*== Actualizar datos ==*/
    $actualizar_cliente=conexion();
    $actualizar_cliente=$actualizar_cliente->prepare("UPDATE clientes SET cliente_nombre=:nombre,cliente_telefono=:telefono WHERE cliente_id=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":telefono"=>$telefono,
        ":id"=>$id
    ];

    if($actualizar_cliente->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡CLIENTE ACTUALIZADO!</strong><br>
                El cliente se actualizo con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el cliente, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_cliente=null;