
<h2>Nuevo Cliente</h2><?php

/*
 * Lista de Clientes
 */ 

require_once("model/cliente.dao.php");
require_once("controller/clientes.controller.php");


?>

<script src="../frameworks/jquery/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="../frameworks/uniform/jquery.uniform.js" type="text/javascript" charset="utf-8"></script> 
<link rel="stylesheet" href="../frameworks/uniform/css/uniform.default.css" type="text/css" media="screen">

<script type="text/javascript" charset="utf-8">
	$(function(){
      $("input, select").uniform();
    });
</script>



<form> 
       
        <div><label>Nombre</label><input type="text" name="nombre" size="40"/></div>
        <div><label>Ciudad</label><input type="text" name="ciudad" size="40"/></div>
        <div><label>Descuento</label><input type="text" name="descuento" size="40"/></div>
        <div><label>Direccion</label><input type="text" name="direccion" size="40"/></div>
        <div><label>E Mail</label><input type="text" name="e_mail" size="40"/></div>
        <label>Sucursal:</label> 
        <select name="sucursal"> 
          <option>Through Google</option> 
          <option>Through Twitter</option> 
          <option>Other&hellip;</option> 
          <option>&lt;Hi&gt;</option> 
        </select>
        <div><label>Limite de credito</label><input type="text" name="limite_credito" size="40"/></div>
        <div><label>RFC</label><input type="text" name="rfc" size="40"/></div>
        <div><label>Telefono</label><input type="text" name="telefono" size="40"/></div>

       <input type="submit" value="Guardar"/> 
       <input type="reset" value="Limpiar forma"/> 
         
       
    </form>

<?php
