<link href="<?= base_url('css/datatables.min.css') ?>" rel="stylesheet" />


<!-- Modal -->
<div class="modal fade" id="form-modal-report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div id="show-laporan">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
	
	<div class="col-md-12">
		<!-- Default box -->
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title"><?= $title ?></h3>
			</div>
			<div class="box-body">
				<table id="datatabel" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
		            <thead>
		                <tr>
		                    <th width="7%">OPSI</th>
		                    <th>NO.STS</th>
		                    <th>TGL PENERIMAAN</th>
		                    <th>TOTAL</th>
		                    <th>JENIS TRANSAKSI</th>
		                    <th>NAMA BANK</th>
		                </tr>
		            </thead>
		        </table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
	
</div>


<script type="text/javascript" src="<?= base_url('js/datatables.min.js') ?>"></script>

<script>
	var modalreport = $( '#form-modal-report' );

	//Data Tabel
    var tabel = $('#datatabel').DataTable({
        'ajax' : {
            'url' : location.href + '/json_data',
            'type' : 'POST',
            'data' : function(data){
                //data.dtstart = $('#startDate').val();
                //data.dtend = $('#endDate').val();
                //data.keterangan = $('#filterketerangan').val();
                //data.draft = $('#filterdraft').val();
            }
        },
        'processing' : true,
        'serverSide' : true,
        'responsive' : true,
        'scrollY' : 350,
        'scrollCollapse' : true,
        "dom": 'rt<"bottom"lp>',
        "order": [[ 2, "desc" ]],
        'paging' : false,
        //"lengthMenu": [[10, 25, 50, -1], [10, 25, 30, "All"]],
        //"searching": false,
        'columns': [
            { "data": "id_sts" },
            { "data": "format_no_sts" },
            { "data": "tgl_batas_sts" },
            { "data": "total" },
            { "data": "jenis_tr_esamsat" },
            { "data": "nama_bank" }
        ],
        //"fixedColumns":   {
        //    leftColumns: 1,
        //    rightColumns: 1
        //},
        "columnDefs": [
            {
                "render": function ( data, type, row ) {
                    return '<button type="button" onClick="clickPdf('+ row['id_sts'] +')" class="btn btn-xs btn-flat" title="Cetak Ulang STS"> <i class="glyphicon glyphicon-print"></i> </button>';
                },
                "searchable" : false,
                "sortable" : false,
                "className": "text-center",
                "targets": 0,
                "responsivePriority": 1
            },
            {
                "targets": 1,
                "responsivePriority": 2,
                "className": "text-center",
            },
            {
                "targets": 2,
                "responsivePriority": 3,
                "className": "text-center",
            },
            {
                "targets": 3,
                "responsivePriority": 2,
                "className": "text-right",
            },
            {
                "targets": 4,
                "className": "text-center",
            },
            {
                "targets": 5,
                "className": "text-center",
            }
        ],
        /*"createdRow": function( row, data, dataIndex ) {
            if ( data['draft'] == 1 ) {
                $(row).addClass( 'draft' );
            }
        }*/
    });


    function clickPdf( val ) {
    	var url = location.href +'/pdf/'+ val;
	    var ifr=$('<iframe/>', {
	        id:'MainPopupIframe',
	        frameborder:0,
	        src: url,
	        style:'display:none;width:100%;height:550px;overflow:scroll;background:#fff;',
	        load:function(){
	            //$(this).show();
	            $(this).show(function () {
	                modalreport.modal('show');
	            });
	        }
	    });
	    $('#show-laporan').html(ifr);
    }
	
</script>