        <style type="text/css" media="print,screen">
            body { font-size: 9pt; margin:0.5em auto; font-family: Courier New,Courier,Lucida Sans Typewriter,Lucida Typewriter,monospace;}
            body { color: #111; }
            h2 {
                margin:0;
                font-weight:bold;
                font-size:10pt;
            }
			#tabel { border-collapse : collapse; width: 100%; page-break-before: always; font-size: 9pt}
            #tabel thead tr th, #tabel tbody tr td, #tabel tfoot tr td { border:1px solid #000; padding:0.5em 0.3em; }
            h1,h2,h3,h4,h5,h6 { margin:0;}
        </style>

        <?php
        error_reporting(0);
        $this->load->view($dinamisContent); 
        ?>
