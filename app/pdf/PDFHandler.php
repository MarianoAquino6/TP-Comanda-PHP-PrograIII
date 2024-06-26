<?php 

class PDFHandler
{
    private $_arrayRecibido;
    private $_nombre;
    private $_titulo;
    private $_logoPath; // Ruta de la imagen del logo

    public function __construct($data, $nombre, $titulo, $logoPath)
    {
        $this->_arrayRecibido = $data;
        $this->_nombre = $nombre;
        $this->_titulo = $titulo;
        $this->_logoPath = $logoPath; // Guardar la ruta de la imagen del logo
    }

    public function createPDF()
    {
        // Crear una instancia de FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        
        // Agregar encabezado con logo y título
        $this->Header($pdf);

        // Título principal en rojo sangre, negrita
        $pdf->SetTextColor(128, 0, 0); // Rojo oscuro
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, $this->_titulo, 0, 1, 'C');
        $pdf->Ln(10); // Espacio vertical

        // Headers de la tabla en negro, negrita
        $pdf->SetTextColor(0); // Negro
        $pdf->SetFont('Arial', 'B', 12);
        if (!empty($this->_arrayRecibido)) {
            // Obtener los encabezados de la primera fila del array
            $headers = array_keys($this->_arrayRecibido[0]);
            foreach ($headers as $header) {
                // Ajustar ancho de celda para el campo "activo" (ejemplo)
                if ($header == 'activo') {
                    $pdf->Cell(20, 10, $header, 1, 0, 'C');
                } else {
                    $pdf->Cell(40, 10, $header, 1, 0, 'C');
                }
            }
            $pdf->Ln();

            // Contenido de la tabla en negro, normal
            $pdf->SetFont('Arial', '', 12);
            foreach ($this->_arrayRecibido as $row) {
                foreach ($row as $column) {
                    // Ajustar ancho de celda para el campo "activo" (ejemplo)
                    if (key($row) == 'activo') {
                        $pdf->Cell(20, 10, $column, 1, 0, 'C');
                    } else {
                        $pdf->Cell(40, 10, $column, 1, 0, 'C');
                    }
                    next($row);
                }
                $pdf->Ln();
            }
        } else {
            $pdf->Cell(0, 10, 'No hay datos disponibles', 1, 1, 'C');
        }

        // Salida del archivo PDF
        $pdf->Output('I', $this->_nombre);
    }

    // Función para agregar el encabezado con logo
    private function Header($pdf)
    {
        // Logo de la compañía a la izquierda
        $pdf->Image($this->_logoPath, 10, 10, 30); // Cambia los valores según la posición y tamaño del logo

        // Título del documento a la derecha
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, "LA COMANDA", 0, 1, 'R');
        $pdf->Ln(10); // Espacio vertical
    }
}