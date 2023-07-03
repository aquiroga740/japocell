<?php
	/*== Almacenando datos ==*/
    $client_id_del=limpiar_cadena($_GET['client_id_del']);

    /*== Verificando usuario ==*/
    $check_cliente=conexion();
    $check_cliente=$check_cliente->query("SELECT cliente_id FROM clientes WHERE cliente_id='$client_id_del'");
    
    if($check_cliente->rowCount()==1){

    	$check_productos=conexion();
    	$check_productos=$check_productos->query("SELECT cliente_id FROM producto WHERE cliente_id='$client_id_del' LIMIT 1");

    	if($check_productos->rowCount()<=0){

    		$eliminar_cliente=conexion();
	    	$eliminar_cliente=$eliminar_cliente->prepare("DELETE FROM clientes WHERE cliente_id=:id");

	    	$eliminar_cliente->execute([":id"=>$client_id_del]);

	    	if($eliminar_cliente->rowCount()==1){
		        echo '
		            <div class="notification is-info is-light">
		                <strong>¡CLIENTE ELIMINADO!</strong><br>
		                Los datos del cliente se eliminaron con exito
		            </div>
		        ';
		    }else{
		        echo '
		            <div class="notification is-danger is-light">
		                <strong>¡Ocurrio un error inesperado!</strong><br>
		                No se pudo eliminar el cliente, por favor intente nuevamente
		            </div>
		        ';
		    }
		    $eliminar_cliente=null;
    	}else{
    		echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                No podemos eliminar el cliente ya que tiene productos asociados
	            </div>
	        ';
    	}
    	$check_productos=null;
    }else{
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El CLIENTE que intenta eliminar no existe
            </div>
        ';
    }
    $check_cliente=null;