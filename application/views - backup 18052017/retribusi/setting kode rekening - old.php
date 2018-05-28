<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.base.css') ?>" type="text/css" />
<link rel="stylesheet" href="<?= base_url('plugins/jqwidget/jqwidgets/styles/jqx.metro.css') ?>" type="text/css" />

<!-- Form Modal Edit -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="title-form">PENCARIAN USER</h4>
      	</div>
      	<div class="modal-body">
			<div class="box-body form-horizontal">
				<div class="form-group">
					<label class="col-sm-2 control-label">Cari NAMA</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="text" id="txt-cari-by-nama" class="form-control input-sm">
							<span class="input-group-btn">
								<button class="btn btn-default input-sm" type="button" id="btn-hasil-pencarian"><i class= "glyphicon glyphicon-search"></i></button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div id="jqxgrid"></div>
		</div>      
    </div>
  </div>
</div>

<!-- ******************************************************************************************************************* -->

<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading" id="titleModal">
			<?= $title ?>	
			</header>
			<div class="panel-body form-horizontal">
				<div class="form-group">
					<label class="col-sm-1 control-label">Tahun </label>
					<div class="col-sm-2">
						<select id="slctth" class="form-control input-sm">
						<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
					</div>
					<label class="col-sm-1 control-label">USER </label>
					<div class="col-sm-3">
						<div class="input-group">
							<input type="hidden" name="hid-id-user" id="hid-id-user">
							<input type="text" class="form-control input-sm" id="dis-user" disabled>
							<span class="input-group-btn">
								<button class="btn btn-default input-sm" type="button" id="btn-cari-user"><i class= "glyphicon glyphicon-user"></i></button>
							</span>
						</div>
					</div>
					<div class="col-sm-3">
						<button type="button" class="btn btn-sm" id="btn-open-pengaturan"> Setting no rekening retribusi </button>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div class="col-sm-12" id="form-setting-kode-rekening" style="display:none;">
		<section class="panel">
			<header class="panel-heading">
				PILIH KODE RETRIBUSI yang DIGUNAKAN :
			</header>
			<div class="panel-body">
				<form class="form-horizontal">
					<div id="result-setting"></div>					
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-flat btn-success"><i class="glyphicon glyphicon-ok-circle"></i> SIMPAN </button>
						<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-batal"><i class="glyphicon glyphicon-remove-circle"></i> BATAL </button>
					</div>
				</form>
			</div>
		</section>
	</div>
</div>


<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxcore.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxbuttons.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxscrollbar.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxmenu.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.sort.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.pager.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxlistbox.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxdropdownlist.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.selection.js') ?>"></script> 
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxgrid.columnsresize.js') ?>"></script> 
<script type="text/javascript" src="<?= base_url('plugins/jqwidget/jqwidgets/jqxdata.js') ?>"></script>

<!-- Form Validation -->
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/formValidation.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/framework/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('plugins/formvalidation/language/id_ID.js') ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
    	//load_opsi_kecamatan_by_filter();

        $("#btn-hasil-pencarian").click(function () {
            $("#jqxgrid").jqxGrid('updatebounddata');
        });
		
		$('#txt-cari-by-nama').on('keyup', function(e){
			if (e.keyCode == 13) {
				$('#btn-cari-user').click();
			}
		});

        $('#btn-cari-user').click(function(){
        	$('#modal-form').modal('show');
			load_daftar_user();
        });
		
		$('#modal-form').on('shown.bs.modal', function () {
			$('#txt-cari-by-nama').focus();
		});
		
		$("#jqxgrid").on('dblclick', function () {
			var selectedrowindex = $("#jqxgrid").jqxGrid('getselectedrowindex');
			var rowscount = $("#jqxgrid").jqxGrid('getdatainformation').rowscount;
			var data=$("#jqxgrid").jqxGrid('getrows')[selectedrowindex];
			$('#hid-id-user').val(data['id_user']);
			$('#dis-user').val(data['username']);
			$('#modal-form').modal('hide');
		});
		
		$('#btn-open-pengaturan').click(function(){
			var id_thn = $('#slctth').val();
			var id_user = $('#hid-id-user').val();
			if ( id_user == '' )
			{
				alert('Silahkan pilih user dahulu !');
			} else {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("tampilkan_data_setting_kode_rekening") ?>',
					dataType: "JSON",
					data: "post_thn="+ id_thn +"&post_user="+ id_user,
					success: function(data) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									var divResult = $("#result-setting");
									divResult.empty();
									var writeHtml = '<ul class="list-unstyled">';
									$.each(data.listpad, function(key, value){
										
										if ( value.anakan == 0 )
										{
											var checkbox = '<input type="checkbox" name="postkoderetribusi[]" value="'+ value.kd_rekening +'"'+ value.checked +'>';
										} else
										{
											var checkbox = '';
										}
										
										writeHtml += '<li><label>'+ checkbox + value.no_rekening +' <span class="text-info">[ '+ value.nm_rekening +' ]</span></label></li>';
									});
									writeHtml += '</ul>';
									divResult.html(writeHtml);
									$('#slctth, #btn-open-pengaturan, #btn-cari-user').attr('disabled', true);
									$('#form-setting-kode-rekening').show('fast');
								}
							});
						}, 1000);
					},
					beforeSend: function() {
						loadoverlay();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									pesanError(xhr.responseText);
								}
							});
						}, 1000);
					}
				});
			}
		});

        $('form').formValidation({
			message: 'This value is not valid',
			excluded: 'disabled',
			icon: {
				valid: '',
				invalid: '',
				validating: ''
			}
		})
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
			
			var id_thn = $('#slctth').val();
			var id_user = $('#hid-id-user').val();
			
			var konfirm = confirm('Apakah data akan disimpan ?');
			if (konfirm == 1) {
				$.ajax({
					type: 'POST',
					url: '<?= base_url("aksi_setting_kode_rekening") ?>',
					dataType: "JSON",
					data: $('form').serialize() +"&post_thn="+ id_thn +"&post_user="+ id_user,
					success: function(html) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									if (html.error == "")
									{
										pesanOK(html.msg);
										$('#btn-batal').click();
									} else {
										pesanError(html.error);
									}
								}
							});
						}, 1000);
					},
					beforeSend: function() {
						loadoverlay();
					},
					error: function(xhr, ajaxOptions, thrownError) {
						setTimeout(function() {
							$.unblockUI({
								onUnblock: function() {
									pesanError(xhr.responseText);
								}
							});
						}, 1000);
					}
				});
			}
            
        });
		
		$('#btn-batal').click(function(){
			$('#form-setting-kode-rekening').hide('fast');
			$('#slctth, #btn-open-pengaturan, #btn-cari-user').attr('disabled', false);
		});
    });
	
	function load_daftar_user()
	{
		var source =
		{
        	datatype: "json",
            url: "<?= base_url('cari_pengguna_pemilihankoderekening') ?>",
		    type : 'post',
            sort: function () {
                // update the grid and send a request to the server.
                $("#jqxgrid").jqxGrid('updatebounddata', 'sort');
            }
        };
		var dataAdapter = new $.jqx.dataAdapter(source,
		    {
		        formatData: function (data) {
		            $.extend(data, {
		            	cariNama : $('#txt-cari-by-nama').val()
		            });
		            return data;
		        }
		    }
		);

        $("#jqxgrid").jqxGrid(
        {
            theme: 'metro',
            width: '100%',
            height: '400',
            source: dataAdapter,
            sortable: true,
            //groupable: true,
            columnsresize: true,
            //altrows: true,
            scrollmode: 'deferred',
            //virtualmode: true,
            deferreddatafields: ['nama_user', 'lokasi'],
            columns: [
        	  {
                  text: '#', sortable: false, filterable: false, editable: false,
                  groupable: false, draggable: false, resizable: false,
                  datafield: '', columntype: 'number', width: 35, cellsalign: 'right', align: 'center',
                  cellsrenderer: function (row, column, value) {
                      return "<div style='margin:5.5px;'>" + (value + 1) + "</div>";
                  }
              },
              { text: 'NAMA LENGKAP', datafield: 'nama_user', width: '58%', cellsalign: 'left', align: 'center' },
              { text: 'UP3AD', datafield: 'lokasi', width: '35%', cellsalign: 'center', align: 'center' }
          ]
        });
	}
</script>