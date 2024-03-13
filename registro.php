<?php
    require_once("conexion/conexion.php");
     $db = new Database();
    $conectar = $db->conectar();
    require 'vendor/autoload.php';
    use Picqer\Barcode\BarcodeGeneratorPNG;
    
    $usua = $conectar->prepare("SELECT * FROM artículos");
    $usua->execute();
    $asigna = $usua->fetchAll(PDO::FETCH_ASSOC);

   if ((isset($_POST["registro"]))&&($_POST["registro"]=="formu"))
   {
   
    $nombre= $_POST['nombre'];
    $precio= $_POST['precio'];
    $codigo_barras = uniqid() . rand(1000, 9999);
    $generator = new BarcodeGeneratorPNG();
    $codigo_barras_imagen = $generator->getBarcode($codigo_barras, $generator::TYPE_CODE_128);
    file_put_contents(__DIR__ . '/images/' . $codigo_barras . '.png', $codigo_barras_imagen);

    $sql=$conectar -> prepare ("SELECT*FROM artículos where nombre = '$nombre'");
    $sql -> execute();
    $fila = $sql -> fetchALL(PDO::FETCH_ASSOC);
    
    if ($nombre=="" || $precio=="" )
    {
    echo '<script>alert("EXISTEN DATOS VACIOS"); </script>';
   
    }
    else if($fila){
    echo '<script>alert("Articulo ya registrado");</script>';
   
    }
    else {


     

        
        $insertSQL = $conectar->prepare("INSERT INTO artículos(nombre, precio, cod_bar) values(?,?,?)");
        $insertSQL -> execute([$nombre, $precio, $codigo_barras]);
       


        
     }  
    }
    
    ?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>formulario de Articulos</title>
	
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">  
</head>



<?php 



?>

<div class="formulario container col-md-4 mx-auto">
        <div class="signup-box">
            
            <h1 class="signup-title">REGISTRO ARTICULOS</h1>
            <br>
            <form method="post" name="formreg" id="formreg" class="signup-form"  autocomplete="off"> 
                <!--Username -->
              
               
                <br>
                <label  for="nombre"></label>
                <br>
                <input class="form-control"     type="text" name="nombre" id="nombres" pattern="[a-zA-Z]{1,40}" title="Solo se permiten letras" placeholder="Digite Nombre">
                <br>
                <label for="precio"></label>
                <br>
                <input class="form-control"     type="int" name="precio" id="precio" pattern="[0-9]{1,15}" title="Solo se permiten numeros" placeholder="precio">
                <br>

                
                
               

               
                

                <input class="b"     type="submit" name="validar" value="Registro">
                <input   type="hidden" name="registro" value="formu">
                </form>
            </div>
                
      
</div>




            <div class="container mt-3">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr style="text-transform: uppercase;">
                            <th>nombre</th>
                            <th>precio</th>
                            <th>codigo de barras</th>
                        </tr>
                    </thead>


            <tbody>
                <?php foreach ($asigna as $usua) { ?>
                    <tr>
                        <td><?= $usua["nombre"] ?></td>
                       
                     <td><img src="images/<?=$usua["cod_bar"] ?>.png" style="max-width: 400px;"></td>
                        <td><?= $usua["precio"] ?></td>
                    </tr>
                <?php  } ?>

            </tbody>                


                
                

</body>
</html>