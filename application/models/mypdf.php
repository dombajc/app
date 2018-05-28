<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author webprog
 */
class Mypdf extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        //$image_file = base_url() . 'images/logopim.jpg';
        //$this->Image($image_file, 15, 5, 10, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        //$this->SetFont('helvetica', 'B', 9);
        // Title
        //$this->Cell(40, 15, 'PT. PRIMA INDO MEGAH', 0, false, 'C', 0, '', 0, false, 'T', 'T');
        //$this->MultiCell(40, 5, ' PT. PRIMA INDO MEGAH', 0,'J',false,1,'','',true,0,false,true,0,'T',false);
    }

    // Page footer
    public function Footer() {
        
        // Position at 15 mm from bottom
        $this->SetY(-5);
        // Set font
        $this->SetFont('helvetica', '', 4);
        // Page number
//        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 0, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

}

?>
