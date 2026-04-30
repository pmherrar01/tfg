<?php
session_start();

require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../models/pedido.php";
require_once __DIR__ . "/../models/producto.php";
require_once __DIR__ . "/../models/usuario.php";
require_once __DIR__ . "/../models/look.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol_id"] != 1) {
    header("Location: ../index.php?error=acceso_denegado");
    exit();
}

$db = new Database();
$conexion = $db->conectar();
$pedido = new Pedido($conexion);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idPedido = isset($_POST["idPedido"]) ? $_POST["idPedido"] : 0;
    $nuevoEstado = isset($_POST["nuevoEstado"]) ? trim($_POST["nuevoEstado"]) : "";

    $accion = isset($_POST["accion"]) ? $_POST["accion"] : "";

    switch ($accion) {
        case "cambiarEstadoPedido":
            $pedido->actualizarEstadoPedido($idPedido, $nuevoEstado);
            header("Location: ../admin/admin.php?seccion=pedidos&mensaje=estado_actualizado");
            break;
        case "actualizarInventarioMasivo":
            $stocks = isset($_POST['stock']) ? $_POST['stock'] : [];
            $rebajas = isset($_POST['rebaja']) ? $_POST['rebaja'] : [];
            $estados = isset($_POST['activo']) ? $_POST['activo'] : [];
            $precios = isset($_POST['precio']) ? $_POST['precio'] : []; 
            $colecciones = isset($_POST['coleccion']) ? $_POST['coleccion'] : []; 

            $pagRetorno = isset($_POST['pagina_retorno']) ? $_POST['pagina_retorno'] : 1;

            $prodObj = new Producto($conexion);

            foreach ($rebajas as $idPrenda => $valorRebaja) {
                $estadoActivo = $estados[$idPrenda];
                $precioActualizado = isset($precios[$idPrenda]) ? $precios[$idPrenda] : null;
                $coleccionActualizada = isset($colecciones[$idPrenda]) ? $colecciones[$idPrenda] : null; 
                
                $prodObj->actualizarDatosBasicosPrenda($idPrenda, $valorRebaja, $estadoActivo, $precioActualizado, $coleccionActualizada);
            }

            foreach ($stocks as $clave => $cantidad) {
                list($idP, $idC, $talla) = explode('_', $clave);
                $prodObj->actualizarStockEspecifico($idP, $idC, $talla, $cantidad);
            }

            header("Location: ../admin/admin.php?seccion=productos&pagina=$pagRetorno&mensaje=inventario_actualizado");
            exit();
            
        case "crearColeccion":
            $nombre = isset($_POST['nombre_coleccion']) ? trim($_POST['nombre_coleccion']) : "";
            $descripcion = isset($_POST['descripcion_coleccion']) ? trim($_POST['descripcion_coleccion']) : "";
            
            if (!empty($nombre)) {
                $prodObj = new Producto($conexion);
                $prodObj->crearColeccion($nombre, $descripcion);
                header("Location: ../admin/admin.php?seccion=colecciones&mensaje=coleccion_creada");
            } else {
                header("Location: ../admin/admin.php?seccion=colecciones&error=nombre_vacio");
            }
            exit();
            

        case 'actualizarColeccion':
            $idCol = isset($_POST['id_coleccion']) ? $_POST['id_coleccion'] : 0;
            $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : "";
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : "";
            $nuevoEstado = isset($_POST['nuevo_estado']) ? $_POST['nuevo_estado'] : 2;
            
            $prodObj = new Producto($conexion);
            $prodObj->actualizarEstadoColeccion($idCol, $nombre, $descripcion, $nuevoEstado);
            
            header("Location: ../admin/admin.php?seccion=colecciones&mensaje=coleccion_actualizada");
            exit();

        case "actualizarRol":
            $idUsuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
            $nuevoRol = isset($_POST['nuevo_rol']) ? (int)$_POST['nuevo_rol'] : 2; 
            
            if ($idUsuario > 0) {
                $userObj = new Usuario($conexion);
                $userObj->actualizarRolUsuario($idUsuario, $nuevoRol);
                header("Location: ../admin/admin.php?seccion=usuarios&mensaje=rol_actualizado");
            } else {
                header("Location: ../admin/admin.php?seccion=usuarios&error=usuario_invalido");
            }
            exit();
        case 'crear_look':
            $prendasRaw = $_POST['prendas'] ?? [];
            $prendasLimpias = [];
            
            foreach ($prendasRaw as $combo) {
                if (!empty($combo) && strpos($combo, '_') !== false) {
                    list($pId, $cId) = explode('_', $combo);
                    $prendasLimpias[] = ['producto_id' => $pId, 'color_id' => $cId];
                }
            }
            
            $lookObj = new Look($conexion);
            if ($lookObj->crearLook($prendasLimpias)) {
                header("Location: ../admin/admin.php?seccion=looks&mensaje=look_creado");
            } else {
                header("Location: ../admin/admin.php?seccion=looks&error=error_creacion");
            }
            exit();

        case 'editar_look':
            $idLook = $_POST['id_look'] ?? 0;
            $activo = $_POST['activo'] ?? 1;
            $prendasRaw = $_POST['prendas'] ?? [];
            
            $prendasLimpias = [];
            
            foreach ($prendasRaw as $combo) {
                if (!empty($combo) && strpos($combo, '_') !== false) {
                    list($pId, $cId) = explode('_', $combo);
                    $prendasLimpias[] = ['producto_id' => $pId, 'color_id' => $cId];
                }
            }
            
            $lookObj = new Look($conexion);
            $lookObj->editarLook($idLook, $activo, $prendasLimpias);
            header("Location: ../admin/admin.php?seccion=looks&mensaje=look_actualizado");
            exit();

        case 'eliminarLook':
            $id = $_POST['id_look'] ?? 0;
            $lookObj = new Look($conexion);
            $lookObj->eliminarLook($id);
            header("Location: ../admin/admin.php?seccion=looks&mensaje=look_eliminado");
            exit();

        case 'crearPrenda':
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = !empty($_POST['precio']) ? str_replace(',', '.', $_POST['precio']) : 0;
            $tipo_id = !empty($_POST['tipo_id']) ? $_POST['tipo_id'] : null;
            $coleccion_id = !empty($_POST['coleccion_id']) ? $_POST['coleccion_id'] : null;
            $genero = !empty($_POST['genero']) ? $_POST['genero'] : 3;
            $color_id = !empty($_POST['color_id']) ? $_POST['color_id'] : null;
            $talla = !empty($_POST['talla']) ? strtoupper($_POST['talla']) : 'U';
            $stock = isset($_POST['stock']) ? (int)$_POST['stock'] : 0;
            
            if (empty($nombre) || empty($color_id)) {
                die("<h2 style='color:red;'>Error crítico: No has puesto el nombre de la prenda o no has seleccionado un color. Dale atrás a tu navegador e inténtalo de nuevo.</h2>");
            }

            $rutasDestino = [];
            if (isset($_FILES['imagenes']) && !empty($_FILES['imagenes']['name'])) {
                $totalImagenes = count($_FILES['imagenes']['name']);
                $rutaDirectorio = __DIR__ . '/../public/img/';
                
                for ($i = 0; $i < $totalImagenes; $i++) {
                    if ($_FILES['imagenes']['error'][$i] === UPLOAD_ERR_OK) {
                        $nombreOriginal = preg_replace("/[^a-zA-Z0-9.-]/", "_", basename($_FILES['imagenes']['name'][$i]));
                        $nombreArchivo = time() . '_' . $i . '_' . $nombreOriginal;
                        
                        if (move_uploaded_file($_FILES['imagenes']['tmp_name'][$i], $rutaDirectorio . $nombreArchivo)) {
                            $rutasDestino[] = 'public/img/' . $nombreArchivo;
                        }
                    }
                }
            }

            $prodObj = new Producto($conexion);
            $resultado = $prodObj->crearPrendaNueva($nombre, $descripcion, $precio, $tipo_id, $coleccion_id, $genero, $color_id, $talla, $stock, $rutasDestino);
            
            if ($resultado === true) {
                header("Location: ../admin/admin.php?seccion=productos&mensaje=prenda_subida");
            } else {
                die("<div style='padding: 30px; border: 2px solid red; background: #ffeeee; color: #900; font-family: sans-serif; max-width: 800px; margin: 50px auto;'>
                        <h2>🚨 ¡Ups! La Base de Datos ha bloqueado la subida</h2>
                        <p>No te preocupes, esto es lo que está fallando exactamente:</p>
                        <pre style='background: #333; color: #fff; padding: 15px; overflow-x: auto; font-size: 16px;'>" . htmlspecialchars($resultado) . "</pre>
                        <p><strong>Cópialo y pégamelo aquí para que lo arreglemos al instante.</strong></p>
                        <br>
                        <button onclick='window.history.back()' style='padding: 10px 20px; cursor: pointer;'>Volver Atrás</button>
                     </div>");
            }
            exit();

        default:
            header("Location: ../admin/admin.php");
            break;
    }
} else {
    header("Location: ../admin/admin.php");
}
exit();
