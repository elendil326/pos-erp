<?php

require_once ('Estructura.php');
require_once("base/producto.dao.base.php");
require_once("base/producto.vo.base.php");
/** Page-level DocBlock .
  * 
  * @author Andres
  * @package docs
  * 
  */
/** Producto Data Access Object (DAO).
  * 
  * Esta clase contiene toda la manipulacion de bases de datos que se necesita para 
  * almacenar de forma permanente y recuperar instancias de objetos {@link Producto }. 
  * @author Andres
  * @access public
  * @package docs
  * 
  */
class ProductoDAO extends ProductoDAOBase
{

	public static function buscarProductos($query, $how_many = 100){
		
		
		/*$sql = "select * from producto where ( nombre_producto like ? or codigo_producto like ? ) and activo = 1 limit ?; ";
		$val = array( "%" . $query . "%" , "%" . $query . "%" , $how_many );*/
		
		
		
		$parts = explode(" ", $query);

		$sql = "select * from producto where (";
		$val = array();
		$first = true;
		foreach ($parts as $p) {
			if($first){
				$first = false;
				
			}else{
				$sql .= " and ";
			}
			$sql .= "  nombre_producto like ? ";
			array_push($val , "%" . $p . "%");
		}
		
		$sql .= " or codigo_producto like ? ) limit 20 ";
		array_push($val , "%" . $query . "%");
		
		global $conn;
		$rs = $conn->Execute($sql, $val);
		$ar = array( );
		foreach ($rs as $foo) {
			$bar =  new Producto($foo);
    		array_push( $ar,$bar);
		}
		return $ar;
	}
  

	public static function ExistenciasTotales($id_producto, $id_lote = null, $id_sucursal = null){
            
            $total = 0;
            
            //calcula las existencias de todos los productos de todos los lotes y todas las sucursales
            if( is_null($id_sucursal) && is_null($id_lote)){                
                
                $lotes = LoteProductoDAO::search( new LoteProducto( array( "id_producto" => $id_producto ) ) );

                foreach($lotes as $l ){
                    $total += $l->getCantidad();
                }		
                
                return $total;
                
            }                                   
            
            //calcula las existencias de un lote en especifico
            if(is_null($id_sucursal) && !is_null($id_lote) ){                                   
                
                $lotes = LoteProductoDAO::search( new LoteProducto( array( "id_producto" => $id_producto, "id_lote" => $id_lote ) ) );

                foreach($lotes as $l ){
                    $total += $l->getCantidad();
                }
                
                return $total;
                
            }                       
            
            //calcula las existencias de un producto de todos los lotes de una sucursal en especifico
            if(!is_null($id_sucursal) && is_null($id_lote) ){   
                
                //obtenemos los lotes de una sucursal
                $almacenes = AlmacenDAO::search(new Almacen(array("id_sucursal" => $id_sucursal)));
                
                //iteramos los almacenes para sacar sus lotes
                foreach($almacenes as $almacen){
                    
                    $lotes = LoteDAO::search(new Lote(array("id_almacen"=>$almacen->getIdAlacen())));
                    
                    //iteramos los lotes para conocer las existencias del producto en ese lote especifico
                    foreach($lotes as $lote){                                                
                        
                        $loteProducto = LoteProductoDAO::search( new LoteProducto( array( "id_producto" => $id_producto, "id_lote" => $lote->getIdLote() ) ) );

                        foreach($loteProducto as $l ){
                            $total += $l->getCantidad();
                        }
                                                                                               
                    }
                    
                }                                
                
                return $total;
                
            }
                
            return $total;
                
	}


	public static function buscarProductoEnSucursal($id_producto, $id_sucursal){
			$sql = "SELECT
				lp.cantidad,
				lp.id_unidad,
				lp.id_lote,
				l.folio
			FROM 
				lote_producto lp,
				lote l,
				sucursal s,
				almacen a
			WHERE 
				lp.id_producto = ?
				and lp.id_lote = l.id_lote
				and l.id_almacen = a.id_almacen
				and a.id_sucursal = ?";
				
			global $conn;
			$rs = $conn->Execute($sql, array($id_producto, $id_sucursal));
			$ar = array( );
			foreach ($rs as $foo) {
	    		array_push( $ar, $foo);
			}
			return $ar;
	}
}
