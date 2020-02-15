<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class rozdelitController extends Controller
{
    //

    public function rozdelitGet()
    {
        // vrati formular pro vlozeni stitku
        return view('rozdelitGet');
    }

    public function rozdelitXMLGet()
    {
        // vrati formular pro vlozeni stitku
        return view('rozdelitXMLGet');
    }

    public function rozdelitPost(Request $request)
    {
        $stitky = $this->rozdelitStitky($request->stitky);
        $stitkyDvakrat = $this->zdvojitStitky($stitky);

        session(['stitky' => $stitkyDvakrat]);

        return view('rozdelitPost', compact('stitkyDvakrat'));
    }

    public function rozdelitXMLPost(Request $request)
    {
        $file = $request->file;
        $xml = xml_parse($file);
        $stitky = $this->rozdelitStitky($xml);
        $stitkyDvakrat = $this->zdvojitStitky($stitky);
        session(['stitky'=> $stitkyDvakrat]);

        return view('rozdelitPost', compact('stitkyDvakrat'));
    }

    public function saveRow(Request $request)
    {
        $stitkyDvakrat = session('stitky');


        $id = $request->id;
        $row = $stitkyDvakrat[$id][2];

        $stitkyDvakrat[$id][0] = substr($row,0,$request->range);
        $stitkyDvakrat[$id][1] = substr($row, $request->range);
        $stitkyDvakrat[$id][2] = $row;
        session(['stitky'=> $stitkyDvakrat]);

        return view('rozdelitPost', compact('stitkyDvakrat'));

    }

    public function getXML(Request $request)
    {

    }

    public function SaveXlsGet()
    {
        $stitkyDvakrat = session('stitky');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $spreadsheet->getProperties()
            ->setCreator('DATEL ELEKTRO S.R.O.')
            ->setLastModifiedBy('DATEL ELEKTRO S.R.O.')
            ->setTitle('Rozdělení štítků')
            ->setSubject('Rozdělení štítků')
            ->setDescription('Rozdělení štítků')
            ->setKeywords('Rozdělení štítků')
            ->setCategory('Rozdělení štítků');

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath('storage/logo-ael.jpg');
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);

        $sheet->getColumnDimension('A')->setWidth(38);
        $sheet->getColumnDimension('B')->setWidth(38);
        $sheet->getRowDimension('7')->setRowHeight(36);

        $sheet->mergeCells('A7:B7');
        $sheet->mergeCells('A8:B8');

        $styleArray = [
            'font' => [
                'bold' => true,
                'size' => 18,
                'name' => 'Calibri',
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'ffd7e4bd',
                ],
            ],
        ];

        $sheet->getStyle('A7')->applyFromArray($styleArray);
        $sheet->getStyle('A8')->applyFromArray($styleArray);

        $styleArray2 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $sheet->getStyle('A9:B9')
            ->applyFromArray($styleArray2);

        $sheet->setCellValue('A7', 'KABELOVÉ ŠTÍTKY');
        $sheet->setCellValue('A9', '1.Řádek');
        $sheet->setCellValue('B9', '2.Řádek');

        $i = 10;
        foreach ($stitkyDvakrat as $stitek) {
            $sheet->setCellValue('A' . $i, $stitek[0]);
            $sheet->setCellValue('B' . $i, $stitek[1]);
            $i++;
        }

        $filename = "storage/temp-" . time() . ".xls";

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save($filename);

        //\Debugbar::info($stitkyDvakrat);
        //exit;

        //PDF file is stored under project/public/download/info.pdf
        $file = public_path() . "/" . $filename;

        $headers = array(
            'Content-Type: application/xls',
        );

        return response()->download($file, 'output.xls', $headers);
        //return view('rozdelitPost', compact('stitkyDvakrat'));
    }

    private function rozdelitStitky($stitky)
    {
        $poleStitku = explode("\n", $stitky);
        $i = 0;
        $stitky = array();

        foreach ($poleStitku as $item) {
            if (strlen($item) > 1) {
                $item = str_replace("\r", '', $item);
                $pos = strpos($item, '[');
                if ($pos != null) {
                    $data = explode('[', $item);
                    $item = substr($data[0], -2) . (explode(']', $data[1])[0]);
                }
                if (strpos($item, '/+'))
                    $pos = strpos($item, '/+') + 1;
                elseif (strpos($item, '/='))
                    $pos = strpos($item, '/=') + 1;

                $rows[0] = substr($item, 0, $pos);
                $rows[1] = substr($item, $pos);
                $rows[2] = $item;
                array_push($stitky, $rows);

            }
        }
        return $stitky;
    }

    private function zdvojitStitky($stitky)
    {
        $stitkyDvakrat = [];
        foreach ($stitky as $stitek) {
            array_push($stitkyDvakrat, $stitek);
            array_push($stitkyDvakrat, $stitek);
        }
        return $stitkyDvakrat;
    }

}
