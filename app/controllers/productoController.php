<?php

require_once './models/producto.php';

class ProductoController
{
    private function CrearRespuesta($response, $data, $status = 200)
    {
        $payload = json_encode($data);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    //////////////////////////////////////////// GET /////////////////////////////////////////////////

    public function ObtenerTodosLosProductos($request, $response, $args)
    {
        try 
        {
            $lista = Producto::ObtenerTodos();

            if ($lista == false)
            {
                return $this->CrearRespuesta($response, array("listaProductos" => "No existen productos"));
            }

            return $this->CrearRespuesta($response, array("listaProductos" => $lista));
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    public function ObtenerTodosLosProductosCSV($request, $response, $args)
    {
        try {
            $csv = Producto::ObtenerTodosCSV();


            $response = $response->withHeader('Content-Type', 'text/csv')
                                 ->withHeader('Content-Disposition', 'attachment; filename="productos.csv"');

            $response->getBody()->write($csv);

            return $response;
        } catch (Exception $e) {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    //////////////////////////////////////////// POST /////////////////////////////////////////////////

    public function RegistrarProducto($request, $response, $args)
    {
        try 
        {
            $parametros = $request->getParsedBody();
    
            $nuevoProducto = new Producto($parametros['tipo'], $parametros['codigo'], $parametros['nombre'], $parametros['precio']);
            $resultado = $nuevoProducto->Registrar();
    
            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Producto creado con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al crear el producto");
            }
        } 
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    // public function ImportarProductos($request, $response, $args)
    // {
    //     try
    //     {
    //         //$encabezadoEsperado = ['is_deleted', 'tipo', 'codigo', 'nombre', 'precio'];
    //         $archivo = fopen($_FILES['csv']['tmp_name'], 'r');

    //         // Leer y descartar la primera línea (encabezado)
    //         fgetcsv($archivo, 1000, ",");

    //         while (($row = fgetcsv($archivo, 1000, ",")) != FALSE) 
    //         {
    //             // Si no existe en la base lo creo y registro
    //             if (!Producto::ProductoExiste($row[2]))
    //             {
    //                 $nuevoProducto = new Producto($row[1], $row[2], $row[3], $row[4]);
    //                 $resultado = $nuevoProducto->Registrar();
    //             }

    //             // En caso de que ya existe lo actualizo
    //             if (Producto::ProductoExiste($row[2]))
    //             {
    //                 $productoAActualizar = Producto::ObtenerUno($row[2]);

    //                 // Si corresponde borrarlo
    //                 if ($row[0] == "1")
    //                 {
    //                     $productoAActualizar->Borrar();
    //                 }
    //                 // Si corresponde actualizar el precio
    //                 if ((double)$row[4] != $productoAActualizar->GetPrecio())
    //                 {
    //                     $productoAActualizar->ActualizarPrecio($row[4]);
    //                 }
    //             }
    //         }
    //         fclose($archivo);

    //         if ($resultado) 
    //         {
    //             return $this->CrearRespuesta($response, array("mensaje" => "Importacion exitosa"));
    //         }
    //     }
    //     catch (Exception $e) 
    //     {
    //         return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
    //     }
    // }

    public function ImportarProductos($request, $response, $args)
    {
        try
        {
            //$encabezadoEsperado = ['is_deleted', 'tipo', 'codigo', 'nombre', 'precio'];
            $archivo = fopen($_FILES['csv']['tmp_name'], 'r');
            
            // Leer y descartar la primera línea (encabezado)
            fgetcsv($archivo, 1000, ",");
            
            while (($row = fgetcsv($archivo, 1000, ",")) != FALSE) 
            {
                // Si no existe en la base lo creo y registro
                if (!Producto::ProductoExiste($row[2]))
                {
                    $nuevoProducto = new Producto($row[1], $row[2], $row[3], $row[4]);
                    $resultado = $nuevoProducto->Registrar();
                }

                // En caso de que ya existe lo actualizo
                if (Producto::ProductoExiste($row[2]))
                {
                    $productoAActualizar = Producto::ObtenerUno($row[2]);

                    // Si corresponde borrarlo
                    if ($row[0] == "1")
                    {
                        $productoAActualizar->Borrar();
                    }
                    // Si corresponde actualizar el precio
                    if ((double)$row[4] != $productoAActualizar->GetPrecio())
                    {
                        $productoAActualizar->ActualizarPrecio($row[4]);
                    }
                }
            }
            fclose($archivo);

            // Si $resultado no está definido, significa que no hubo ninguna acción, entonces definirlo como true
            if (!isset($resultado)) {
                $resultado = true;
            }

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Importacion exitosa"));
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }

        // Devolver una respuesta por defecto si $resultado no está definido
        return $this->CrearRespuesta($response, array("mensaje" => "No se realizaron cambios"));
    }

    //////////////////////////////////////////// PUT /////////////////////////////////////////////////

    public function ActualizarPrecioProducto($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $productoAActualizar = Producto::ObtenerUno($parametros['codigo']);
            $resultado = $productoAActualizar->ActualizarPrecio($parametros['precio']);

            if ($resultado) 
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Precio actualizado con exito"));
            } 
            else 
            {
                throw new Exception("Ha surgido un error al actualizar el precio");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }

    //////////////////////////////////////////// DELETE /////////////////////////////////////////////////

    public function BorrarProducto($request, $response, $args)
    {
        try
        {
            $parametros = $request->getParsedBody();
            $productoABorrar = Producto::ObtenerUno($parametros['codigo']);
            $resultado = $productoABorrar->Borrar();

            if ($resultado)
            {
                return $this->CrearRespuesta($response, array("mensaje" => "Producto borrado con exito"));
            }
            else
            {
                throw new Exception("Ha surgido un error al borrar el producto");
            }
        }
        catch (Exception $e) 
        {
            return $this->CrearRespuesta($response, array("mensaje" => $e->getMessage()), 500);
        }
    }
}