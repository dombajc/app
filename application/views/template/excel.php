<style type="text/css" media="print,screen">
    body { font-size: 12pt; margin:0.5em auto; font-family: Georgia, 'Times New Roman', serif;}
    h3 {
        margin:0;
        font-weight:bold;
        font-size:11pt;
    }
	#tabel { width: 100%; page-break-before: always;}
    #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000; padding:0.5em 0.3em; }
    h1,h2,h3,h4,h5,h6 { margin:0;}
	.str2{ mso-number-format:\@; }
</style>
<?php
// The function header by sending raw excel
error_reporting(E_ERROR | E_WARNING | E_PARSE);

header("Content-type: application/vnd-ms-excel");

// Defines the name of the export file "codelution-export.xls"
header("Content-Disposition: attachment; filename=". $namafile .".xls");
$this->load->view($dinamisContent);
?>