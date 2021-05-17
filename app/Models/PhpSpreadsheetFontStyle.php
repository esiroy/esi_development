<?php

namespace App\Models;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\PhpSpreadsheetFontStyle as Style;
use Illuminate\Database\Eloquent\Model;

class PhpSpreadsheetFontStyle extends Model
{
    public static function init() 
    {
        //Init
        $spreadsheet = new Spreadsheet();     

        //STYLE
        $styleArrayH1 = Style::setHeader();
        
        //general style
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);

        //set initial width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(35);

        //Header Style
        $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($styleArrayH1);

        //Merge Header
        $spreadsheet->getActiveSheet()->mergeCells('B1:I1');        
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);

        return $spreadsheet;
    }


    public static function init_report() 
    {
        //Init
        $spreadsheet = new Spreadsheet();     

        //STYLE
        $styleArrayH1 = Style::setHeader();
        
        //general style
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);

        //set initial width
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(35);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(35);

        //Header Style
        $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($styleArrayH1);

        //Merge Header
        $spreadsheet->getActiveSheet()->mergeCells('A1:I1');        
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);

        return $spreadsheet;
    }
    
    
    public static function setHeader($fontColor = 'FFFFFF', $bgColor = "0000c1", $fonSize = 25) 
    {
        $styleArray = [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'size' => $fonSize,
                'color' => ['argb' => $fontColor],             
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => $bgColor,
                ]              
            ],            
            /*
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            */
        ];
        return $styleArray;
    }
}
