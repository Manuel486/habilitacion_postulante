<?php

require_once MODELS_PATH . "/PreseleccionadoModel.php";
require_once __DIR__ . '/../phpspreadsheet/vendor/autoload.php';
require_once CONTROLLERS_PATH . "/helpers/ApiRespuesta.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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
            $id_requerimiento = $_POST["id_requerimiento"] ?? "";

            $whereIdRequerimiento = "";
            $executeRequerimiento = [];
            if ($id_requerimiento !== "") {
                $whereIdRequerimiento = "WHERE pr.id_reque_proy = :id_requerimiento";
                $executeRequerimiento = [
                    "id_requerimiento" => $id_requerimiento
                ];
            }

            $data = $this->preseleccionadoModel->obtenerPreseleccionados($whereIdRequerimiento, $executeRequerimiento);

            $filtro_columnas = json_decode($_POST["filtro_columnas"] ?? '[]');
            $filtro_columnas = array_map(function ($col) {
                return trim(urldecode($col));
            }, $filtro_columnas);

            if (empty($data)) {
                echo "No hay datos para exportar.";
                return;
            }

            $data_filtrada = [];

            if (!empty($filtro_columnas)) {
                foreach ($data as $fila) {
                    $filtrado = [];
                    foreach ($filtro_columnas as $columna) {
                        $filtrado[$columna] = $fila[$columna] ?? '';
                    }
                    $data_filtrada[] = $filtrado;
                }
            } else {
                $data_filtrada = $data;
                $filtro_columnas = array_keys($data[0]);
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = $filtro_columnas;

            $headerStyle = [
                'font' => ['bold' => true],
            ];

            foreach ($headers as $colIndex => $header) {
                $cell = Coordinate::stringFromColumnIndex($colIndex + 1) . '1';
                $sheet->setCellValue($cell, strtoupper($header));
                $sheet->getStyle($cell)->applyFromArray($headerStyle);
            }

            $rowIndex = 2;
            foreach ($data_filtrada as $row) {
                foreach ($headers as $colIndex => $key) {
                    $cell = Coordinate::stringFromColumnIndex($colIndex + 1) . $rowIndex;
                    $sheet->setCellValue($cell, $row[$key]);
                }
                $rowIndex++;
            }

            for ($i = 0; $i < count($headers); $i++) {
                $colLetter = Coordinate::stringFromColumnIndex($i + 1);
                $sheet->getColumnDimension($colLetter)->setAutoSize(true);
            }

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

    public function apiObtenerNombreColumnas()
    {
        try {
            $respuesta = $this->preseleccionadoModel->obtenerNombreColumnas();

            if ($respuesta) {
                echo ApiRespuesta::exitoso($respuesta, "Nombre de columnas obtenidas con éxito.");
            } else {
                echo ApiRespuesta::error("No se pudo obtener el nombre de las columnas");
            }
        } catch (Exception $e) {
            echo "Error al generar Excel: " . $e->getMessage();
        }
    }

}