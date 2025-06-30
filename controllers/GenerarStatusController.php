<?php

require_once MODELS_PATH . "/PreseleccionadoModel.php";
require_once __DIR__ . '/../phpspreadsheet/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
class GenerarStatusController
{
    private $preseleccionadoModel;

    public function __construct()
    {
        $this->preseleccionadoModel = new PreseleccionadoModel();
    }

    public function generarExcelStatus()
    {
        try {
            $id_requerimiento = $_POST["id_requerimiento"];
            $data = $this->preseleccionadoModel->obtenerPreseleccionados($id_requerimiento);

            if (empty($data)) {
                echo "No hay datos para exportar.";
                return;
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Encabezados
            $headers = array_keys($data[0]);

            $headerStyle = [
                'font' => ['bold' => true],
                // 'fill' => [
                //     'fillType' => Fill::FILL_SOLID,
                //     'startColor' => ['rgb' => 'FFFF00'],
                // ],
            ];

            // Escribir encabezados
            foreach ($headers as $colIndex => $header) {
                $cell = chr(65 + $colIndex) . '1';
                $sheet->setCellValue($cell, strtoupper($header));
                $sheet->getStyle($cell)->applyFromArray($headerStyle);
            }

            // Escribir datos
            $rowIndex = 2;
            foreach ($data as $row) {
                $colIndex = 0;
                foreach ($headers as $key) {
                    $cell = chr(65 + $colIndex) . $rowIndex;
                    $sheet->setCellValue($cell, $row[$key]);
                    $colIndex++;
                }
                $rowIndex++;
            }

            // Autoajuste de columnas
            foreach (range('A', chr(65 + count($headers) - 1)) as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Descargar directamente en el navegador
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="preseleccionados.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;

        } catch (Exception $e) {
            echo "Error al generar Excel: " . $e->getMessage();
        }
    }

}