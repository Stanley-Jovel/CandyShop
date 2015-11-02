<?php
	require_once(__ROOT__.'/conexion.php');
	//$query = "SELECT * FROM categoria order by idcategoria asc";
	function getCategorias($id="%"){
		global $db;
		$stmt = $db->prepare("SELECT * FROM categoria WHERE idcategoria LIKE ? ORDER BY idcategoria asc");
		$tipo = $id=="%"?"s":"i";
		$stmt->bind_param($tipo,$id);	
		$rows = array();
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				array_push($rows, $row);
			}
		}
		return $rows;
	}
	function getUsuario($id="%"){
		global $db;
		$stmt = $db->prepare("SELECT * FROM usuarios WHERE Id LIKE ?");
		$tipo = $id=="%"?"s":"i";
		$stmt->bind_param($tipo,$id);
		$rows = array();
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				array_push($rows, $row);
			}
		}
		return $rows;
	}
	function getProductoPorCategoria($idcategoria="%", $nombre_poducto="%"){
		global $db;
		$stmt = $db->prepare("SELECT * FROM productos WHERE idcategoria LIKE ? AND nombre LIKE ?");
		$tipo = $idcategoria=="%"?"s":"i";
		$nombre_poducto = "%".$nombre_poducto."%";
		$stmt->bind_param($tipo."s",$idcategoria,$nombre_poducto);
		$rows = array();
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			while ($row = $result->fetch_assoc()) {
				array_push($rows, $row);
			}
		}
		return $rows;
	}
	function getProductoPorId($id="%", $assoc = true){
		global $db;
		$stmt = $db->prepare("SELECT * FROM productos WHERE Id LIKE ?");
		$tipo = $id=="%"?"s":"i";
		$stmt->bind_param($tipo,$id);
		$rows = array();
		if ($stmt->execute()) {
			$result = $stmt->get_result();

			if ($assoc) {
				while ($row = $result->fetch_assoc()) {
					array_push($rows, $row);
				}
			} else {
				while ($row = $result->fetch_array()) {
					array_push($rows, $row);
				}
			}

		}
		return $rows;
	}
	function productoVisto($idproducto, $idusuario){
		global $db;
		$stmt = $db->prepare("INSERT INTO visita (idproducto, idusuario) VALUES (?, ?)");
		$stmt->bind_param("ii",$idproducto, $idusuario);
		$stmt->execute();
		$stmt->close();
	}
	// Funcion para Mostrar las ultimas vistas
	function getUltimasVistas($idusuario)
	{
		global $db;
		$query = 
			"SELECT DISTINCT 
				ordenada.*
			FROM 
				(
					SELECT 
						p.*,v.idusuario 
					FROM 
						visita v
					INNER JOIN
						productos p
					ON
						p.id = v.idproducto
					ORDER BY 
						created_at DESC
				) ordenada
			WHERE
				ordenada.idusuario=?
			LIMIT
				0,5";
		$stmt = $db->prepare($query);
		$stmt->bind_param("i",$idusuario);
		$rows = array();		
		if ($stmt->execute()) {
			$result = $stmt->get_result();
			while ($row = $result->fetch_array()) {
				array_push($rows, $row);
			}
		}
		return $rows;
	}
?>