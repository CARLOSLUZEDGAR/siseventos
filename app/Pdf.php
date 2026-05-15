<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pdf extends Model
{
    //
    public function Header()
    {
        //$this->fpdf->SetFont('Arial','I',8);
        
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->SetY(0);
        $this->fpdf->SetX(30);
        $fecha=date("dHi-M-Y");
        //$this->fpdf->Image('../../../imagenes_qr/'.$fecha.'.png',166,20,30,30);
        $this->fpdf->Cell(166,20,utf8_decode($fecha),1,0,'R');
        //$this->fpdf->Ln();
    }
    public function Footer()
    {
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->SetY(0);
        $this->fpdf->SetX(30);
        $this->fpdf->Cell(166,20,utf8_decode('Desarrollado por: DIGETIC - Secc. Desarrollo de Sistemas'),1,0,'R');
        $this->fpdf->SetY(0);
        $this->fpdf->SetX(30);
        $this->fpdf->Cell(166,20,$this->fpdf->PageNo().' '.'-'.' '.'{nb}',1,0,'C');
    }
}
