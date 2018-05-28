<!-- CSS Serialize  -->
<link href="<?= base_url('plugins/select2/select2.min.css') ?>" rel="stylesheet" />
<link href="<?= base_url('plugins/select2/select2-bootstrap.min.css') ?>" rel="stylesheet" />

<div class="row">
	<div class="col-lg-6">
		<section class="panel">
			<header class="panel-heading">
				Pencarian Riwayat berdasarkan :
			</header>
			<div class="panel-body form-horizontal">
				<div class="form-group">
				  	<label class="col-sm-4 control-label">Pilih Tahun Anggaran</label>
				  	<div class="col-sm-3">
						<select name="slctth" id="slctth" class="form-control">
							<?= $this->Thanggaran->opsiByTahunAktif() ?>
						</select>
				  	</div>
				  	<label class="col-sm-3 control-label">Triwulan</label>
				  	<div class="col-sm-2">
						<select name="slcttriwulan" id="slcttriwulan" class="form-control">
							<?= $this->Triwulan->optionSelect() ?>
						</select>
				  	</div>
				</div>
				<div class="form-group">
				  	<label class="col-sm-4 control-label">Cari Pegawai</label>
				  	<div class="col-sm-8">
						<select name="slctpegawai" id="slctpegawai" class="form-control">
						</select>
				  	</div>
				</div>
				<div class="form-group">
				  	<label class="col-sm-4 control-label">Lokasi Aktif</label>
				  	<div class="col-sm-8">
						<select name="slctlokasi" id="slctlokasi" class="form-control">
						</select>
				  	</div>
				</div>
			</div>
		</section>
	</div>

	<div class="col-lg-6" id="div-form-inputan">
		<section class="panel">
			<header class="panel-heading">
				FORM RIWAYAT LOKASI PEGAWAI :
			</header>
			<div class="panel-body">
				<form class="form-horizontal">
					<input type="hidden" name="haksi" id="haksi">
					<input type="hidden" name="hID" id="hID">
					<div class="form-group">
					  	<label class="col-sm-3 control-label">Lokasi</label>
					  	<div class="col-sm-9">
							<select name="slctlokasi" id="slctlokasi" class="form-control">
								<?= $this->Lokasi->optionAll() ?>
							</select>
					  	</div>
					</div>
					<center>
						<button type="submit" class="btn btn-sm btn-flat btn-success"><i class="glyphicon glyphicon-ok-circle"></i> SIMPAN </button>
						<button type="button" class="btn btn-sm btn-flat btn-danger" id="btn-batal"><i class="glyphicon glyphicon-remove-circle"></i> BATAL </button>
					</center>
				</form>
			</div>
		</section>
	</div>

</div>

<!-- Serialize -->
<script type="text/javascript" src="<?= base_url('plugins/select2/select2.min.js') ?>"></script>

<script>

	$(function(){
		$("#slctpegawai").select2({
			  theme: "bootstrap",
			  ajax: {
				url: "<?= base_url('get_select_by_nama_pegawai') ?>",
				dataType: 'json',
				delay: 250,
				data: function (params) {
				  return {
					q: params.term, // search term
					page: params.page
				  };
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			  },
			  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			  minimumInputLength: 1,
			  //formatResult: format,
	          //formatSelection: format
			  templateResult: formatRepo, // omitted for brevity, see the source of this page
			  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
		}).on("select2:select", function(e) {
			//$('#btn-tambah').attr('disabled', false);
        });

        $("#slctlokasi").select2({
			  theme: "bootstrap",
			  ajax: {
				url: "<?= base_url('get_selet_lokasi_aktif_by_pegawai') ?>",
				dataType: 'json',
				delay: 250,
				data: function (params) {
				  return {
					q: params.term, // search term
					page: params.page,
					idp : $('#slctpegawai').val()
				  };
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2.
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			  },
			  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			  minimumInputLength: 1,
			  //formatResult: format,
	          //formatSelection: format
			  templateResult: formatRepo2, // omitted for brevity, see the source of this page
			  templateSelection: formatRepoSelection2 // omitted for brevity, see the source of this page
		});
	});

	function formatRepo (repo) {
		if (repo.loading) return repo.text;

		var markup = "<div class='select2-result-repository clearfix'>" +
				"<div class='select2-result-repository__avatar'>" + repo.nama_pegawai + "</div>" +
					"<div class='select2-result-repository__meta'>" +
					"<div class='select2-result-repository__title'>NIP : " + repo.nip + "</div>"+
				"</div>"+
			"</div>";

		  return markup;
    }

    function formatRepoSelection (repo) {
		return repo.nama_pegawai || repo.text;
    }

    function formatRepo2 (repo) {
		if (repo.loading) return repo.text;

		var markup = "<div class='select2-result-repository clearfix'>" +
				"<div class='select2-result-repository__avatar'>" + repo.lokasi + "</div>" +
					"<div class='select2-result-repository__meta'>" +
					"<div class='select2-result-repository__title'>" + repo.alamat + "</div>"+
				"</div>"+
			"</div>";

		  return markup;
    }

    function formatRepoSelection2 (repo) {
		return repo.lokasi || repo.text;
    }

</script>