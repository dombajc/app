$(function(){
	$('#txtkeluhan').focus();
	get_status_pemeriksaan();
	
	$('#btn-cari-obat').click(function(){
		$('#formModal').modal('show');
		$('#slctobat').select2("val", "");
		$('#slctobat').text('');
		$('#form-tambah-item')[0].reset();
		$('#form-tambah-item').formValidation('resetForm', true);
	});
	
	$('#btn-cari-biaya-medis').click(function(){
		$('#formModal2').modal('show');
		$('#slctmedis').select2("val", "");
		$('#slctmedis').text('');
		$('#form-tambah-item2')[0].reset();
		$('#form-tambah-item2').formValidation('resetForm', true);
	});
	
	$("#slctobat").select2({
		theme: "bootstrap",
		ajax: {
			url: link_select2_obat,
			dataType: 'json',
			delay: 250,
			data: function (params) {
			  return {
				q: params.term
			  };
		},
		processResults: function (data) {
			// parse the results into the format expected by Select2.
			// since we are using custom formatting functions we do not need to
			// alter the remote JSON data
			return {
				results: data
			};
		},
			cache: true
		},
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 1
	});
	
	$("#slctmedis").select2({
		theme: "bootstrap",
		ajax: {
			url: link_select2_tindakan,
			dataType: 'json',
			delay: 250,
			data: function (params) {
			  return {
				q: params.term
			  };
		},
		processResults: function (data) {
			// parse the results into the format expected by Select2.
			// since we are using custom formatting functions we do not need to
			// alter the remote JSON data
			return {
				results: data
			};
		},
			cache: true
		},
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 1
	});
	
	$('#form-tambah-item')
	.formValidation({
		message: 'This value is not valid',
		excluded: 'disabled',
		icon: {
			valid: '',
			invalid: '',
			validating: ''
		},
		fields: {
			slctobat: {
				validators: {
					notEmpty: {
						message: 'Pilih Obat yang akan di beli !'
					}
				}
			},
			txtjmlbeli: {
				validators: {
					notEmpty: {
						message: 'Tidak boleh kosong !'
					}
				}
			}
		}
	})
	.on('success.form.fv', function(e) {
		// Prevent form submission
		e.preventDefault();
		
		var id_obat = $('#slctobat').val();
		var nama_obat = $('#slctobat').text();
		var jumlah = $('#txtjmlbeli').val();
		
		$('#grid3 tbody').append('<tr>'+
		'<td><input type="hidden" name="hidobat[]" value="'+ id_obat +'">'+ nama_obat +'</td>'+
		'<td class="text-right"><input type="hidden" name="hjml[]" value="'+ jumlah +'">'+ accounting.formatMoney(jumlah, "", 0) +'</td>'+
		'<td width="3%" class="text-center"><button type="button" class="btn btn-sm btn-danger" onClick="deleteRow(this)"><i class="glyphicon glyphicon-remove"></i></button></td>'+
		'</tr>');
		
		$('#formModal').modal('hide');
	});
	
	$('#form-tambah-item2')
	.formValidation({
		message: 'This value is not valid',
		excluded: 'disabled',
		icon: {
			valid: '',
			invalid: '',
			validating: ''
		},
		fields: {
			slctmedis: {
				validators: {
					notEmpty: {
						message: 'Pilih salah satu Biaya Medis !'
					}
				}
			}
		}
	})
	.on('success.form.fv', function(e) {
		// Prevent form submission
		e.preventDefault();
		
		var id_biaya_medis = $('#slctmedis').val();
		var tindakan_medis = $('#slctmedis').text();
		
		$('#grid4 tbody').append('<tr>'+
		'<td><input type="hidden" name="hidbiayamedis[]" value="'+ id_biaya_medis +'">'+ tindakan_medis +'</td>'+
		'<td width="3%" class="text-center"><button type="button" class="btn btn-sm btn-danger" onClick="deleteRow(this)"><i class="glyphicon glyphicon-remove"></i></button></td>'+
		'</tr>');
		
		$('#formModal2').modal('hide');
		
	});
	
	$('#form-rekam-medis')
	.formValidation({
		message: 'This value is not valid',
		excluded: 'disabled',
		icon: {
			valid: '',
			invalid: '',
			validating: ''
		},
		fields: {
			txtkeluhan: {
				validators: {
					notEmpty: {
						message: 'Tidak boleh kosong !'
					}
				}
			},
			txtanamnesis: {
				validators: {
					notEmpty: {
						message: 'Tidak boleh kosong !'
					}
				}
			},
			txtdiagnosis: {
				validators: {
					notEmpty: {
						message: 'Tidak boleh kosong !'
					}
				}
			}
		}
	})
	.on('success.form.fv', function(e) {
		// Prevent form submission
		e.preventDefault();
		
		var konfirm = confirm('Rekam medis akan di simpan ?');
		if (konfirm == 1) {
			$.ajax({
				type: 'POST',
				url: link_simpan_rekam_medis,
				dataType: "JSON",
				data: $('#form-rekam-medis').serialize(),
				success: function(html) {
					setTimeout(function() {
						$.unblockUI({
							onUnblock: function() {
								if (html.error == "")
								{
									pesanOK(html.msg);
									if ( html.result_status === '33' )
									{
										location.href = url_utama;
									}
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
	
	$('#btn-show-riwayat-rekam-medis').click(function(){
		$('#formModal3').modal('show');
		$("#grid").bootgrid({
			ajax: true,
			post: function ()
			{
				/* To accumulate custom parameter with the request object */
				return {
					sIDPasien : sIDPasien,
					sID : $('#hID').val()
				};
			},
			url: json_riwayat_rekam_medis,
			selection: true,
			multiSelect: false,
			multiSort : true,
			rowSelect: true,
			keepSelection: true,
			templates: {
				header: "<div id=\"{{ctx.id}}\" class=\"{{css.header}}\"><div class=\"row\"><div class=\"col-sm-12 actionBar\"><p class=\"{{css.actions}}\"></p></div></div></div>"       
			},
			formatters: {
				"commands": function(column, row)
				{
					var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default command-view\" data-row-id=\"" + row.id_antrian_rekam_medis+ "\"><span class=\"glyphicon glyphicon-eye-open text-green\"></span></button> " + 
					"<button type=\"button\" class=\"btn btn-xs btn-default command-edit\" data-row-id=\"" + row.id_antrian_rekam_medis+ "\"><span class=\"fa fa-pencil text-blue\"></span></button> " + 
					"<button type=\"button\" class=\"btn btn-xs btn-default command-delete\" data-row-id=\"" + row.id_antrian_rekam_medis+ "\"><span class=\"fa fa-trash-o text-red\"></span></button>";
					
					return showButton;
				}
			}
		}).on("loaded.rs.jquery.bootgrid", function()
		{
			/* Executes after data is loaded and rendered */
			$("#grid").find(".command-view").on("click", function(e)
			{
				loadModalDetil($(this).data("row-id"));
			}).end().find(".command-edit").on("click", function(e)
			{
				location.href = get_url_update_rekam_medis + '/' + $(this).data("row-id");
			}).end().find(".command-delete").on("click", function(e)
			{
				actDelete($(this).data("row-id"));
			});
		});
	});
	
	/*----------------------------------
			Setting up the web camera
	----------------------------------*/

	
	webcam.set_swf_url( link_swf_webcam );
	webcam.set_api_url( site_upload );	// The upload script
	webcam.set_quality(90);				// JPEG Photo Quality
	webcam.set_shutter_sound(true, sound_webcam );

	// Generating the embed code and adding it to the page:	
	screen.html(
		webcam.get_html(screen.width(), screen.height())
	);
	
	$('#btnOpenUpload').click(function(){
		$('#formModal5').modal('show');
		siapFoto();
	});
	
	var shootEnabled = false;
	
	$('#btn-ambil-foto').click(function(){
		if(!shootEnabled){
			return false;
		}
		
		webcam.freeze();
		siapUnggah();
		return false;
	});
	
	$('#btn-ulangi').click(function(){
		webcam.reset();
		siapFoto();
		return false;
	});
	
	$('#btn-upload').click(function(){
		var ID = $('#hID').val();
		webcam.upload(site_upload +'/id/'+ ID);
		webcam.reset();
		siapFoto();
		return false;
	});
	
	camera.find('.settings').click(function(){
		if(!shootEnabled){
			return false;
		}
		
		webcam.configure('camera');
	});
	
	/*----------------------
	Callbacks
	----------------------*/

	webcam.set_hook('onLoad',function(){
		// When the flash loads, enable
		// the Shoot and settings buttons:
		shootEnabled = true;
	});
	
	webcam.set_hook('onComplete', function(msg){

		// This response is returned by upload.php
		// and it holds the name of the image in a
		// JSON object format:

		msg = $.parseJSON(msg);

		if(msg.error){
			pesanError(msg.error);
		}
		else {
			// Adding it to the page;
			pesanOK(msg.msg);
			open_modal_edit_foto();
			$('#idgambar').val(msg.id);
			$('#foto-rekam-medis').attr('src', link_load_foto + '/' + msg.namafoto);
		}
		load_galeri();
	});

	webcam.set_hook('onError',function(e){
		screen.html(e);
	});
	
	$('.nav-tabs a').on('shown.bs.tab', function(event){
		var hasil = $(event.target).text();
		if ( hasil == 'Foto Rekam Medis' ){
			//$('#grid2').bootgrid('reload');
			load_galeri();
		}
	});
	
	$('#form-gambar')
	.formValidation({
		message: 'This value is not valid',
		excluded: 'disabled',
		icon: {
			valid: '',
			invalid: '',
			validating: ''
		},
		fields: {
			
		}
	})
	.on('success.form.fv', function(e) {
		// Prevent form submission
		e.preventDefault();
		
		var konfirm = confirm('Simpan Foto Rekam Medis ?');
		if (konfirm == 1) {
			$.ajax({
				type: 'POST',
				url: link_simpan_foto_rekam_medis,
				dataType: "JSON",
				data: $('#form-gambar').serialize(),
				success: function(html) {
					setTimeout(function() {
						$.unblockUI({
							onUnblock: function() {
								if (html.error == "")
								{
									pesanOK(html.msg);
									$('#formModal6').modal('hide');
									load_galeri();
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
	
	$('#batalRekamMedis').click(function(){
		var konfirm = confirm('Batalkan pemeriksaan rekam medis. Semua Foto dan data akan di hapus dan pasien akan di kembalikan ke status antrian ?');
		
		if (konfirm == 1) {
			$.ajax({
				type: 'POST',
				url: link_aksi_rekam_medis,
				dataType: "JSON",
				data: 'haksi=delete&hID=' + id_antrian,
				success: function(html) {

					setTimeout(function() {
						$.unblockUI({
							onUnblock: function() {
								if (html.error == "")
								{
									pesanOK(html.msg);
									location.href = url_utama;
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
	
});

function deleteRow(btn) {
		var row = btn.parentNode.parentNode;
		row.parentNode.removeChild(row);
}

function siapUnggah(){
			
	$('#btn-ambil-foto').hide('fast');
	$('#btn-ulangi').show('fast');
	$('#btn-upload').show('fast');
}

function siapFoto(){
	$('#btn-ambil-foto').show('fast');
	$('#btn-ulangi').hide('fast');
	$('#btn-upload').hide('fast');
}

function loadModalDetil(ID)
{
	$.ajax({
		type: 'POST',
		url: json_detil_rekam_medis,
		dataType: "JSON",
		data: 'hID=' + ID,
		success: function(html) {

			setTimeout(function() {
				$.unblockUI({
					onUnblock: function() {
						if (html.status == 1)
						{
							$('#formModal4').modal('show');
							$('#pTgl').text(html.tgl_daftar);
							$('#pDokter').text(html.nama_dokter);
							$('#pKeluhan').text(html.keluhan);
							$('#pAnamnesis').text(html.anamnesis);
							$('#pFisik').text(html.fisik);
							$('#pPenunjang').text(html.penunjang);
							$('#pDiagnosis').text(html.diagnosis);
							$('#pTerapi').text(html.terapi);
							$('#pAlergi').text(html.alergi_obat);
							$('#pPengobatan').text(html.pengobatan);
							
							var $tabel_rekam_obat = $("#tabel-rekam-obat tbody");
							$tabel_rekam_obat.empty();
							$.each(html.arrObat, function(key, value) {
								$tabel_rekam_obat.append('<tr><td>'+ value.nama_obat +'</td><td class="text-right">'+ value.jml +'</td></tr>');
							});
							
							var $tabel_rekam_tindakan = $("#tabel-rekam-tindakan tbody");
							$tabel_rekam_tindakan.empty();
							$.each(html.arrTindakan, function(key, value) {
								$tabel_rekam_tindakan.append('<tr><td>'+ value.tindakan_medis +'</td></tr>');
							});
							
							$('.docs-pictures-history').viewer('destroy');
							var $tabelgaleri = $('.docs-pictures-history');
							
							$tabelgaleri.empty();
							$.each(html.arrPoto, function(key, value) {
								$tabelgaleri.append('<li><img data-original="'+ link_load_foto + '/' + value.gambar +'" src="'+ link_load_foto + '/' + value.gambar +'" alt="'+ value.keterangan +'"></li>');
							});
							$('.docs-pictures-history').viewer();
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

function actDelete(ID)
{
	var konfirm = confirm('Batalkan pemeriksaan rekam medis. Semua Foto dan data akan di hapus dan pasien akan di kembalikan ke status antrian ?');
		
	if (konfirm == 1) {
		$.ajax({
			type: 'POST',
			url: link_aksi_rekam_medis,
			dataType: "JSON",
			data: 'haksi=delete&hID=' + ID,
			success: function(html) {

				setTimeout(function() {
					$.unblockUI({
						onUnblock: function() {
							if (html.error == "")
							{
								$('#grid').bootgrid('reload');
								pesanOK(html.msg);
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
	
}

function open_modal_edit_foto()
{
	$('#formModal6').modal('show');
	$('#form-gambar')[0].reset();
	$('#form-gambar').formValidation('resetForm', true);
}

function load_galeri()
{
	$.ajax({
		type: 'POST',
		url: json_galeri,
		dataType: "JSON",
		data: 'hID=' + $('#hID').val(),
		success: function(html) {

			setTimeout(function() {
				$.unblockUI({
					onUnblock: function() {
						$('.docs-pictures').viewer('destroy');
						var $tabelgaleri = $('.docs-pictures');
						
						$tabelgaleri.empty();
						$.each(html, function(key, value) {
							$tabelgaleri.append('<li><img data-original="'+ link_load_foto + '/' + value.gambar +'" src="'+ link_load_foto + '/' + value.gambar +'" alt="'+ value.keterangan +'">&nbsp;&nbsp;<button type="button" class="btn btn-sm btn-warning" onclick="formEditFoto('+ value.id_gambar_rekam_medis +');">Edit</button>&nbsp;<button type="button" class="btn btn-sm btn-danger" onclick="formDeleteFoto('+ value.id_gambar_rekam_medis +');">Delete</button></li>');
						});
						$('.docs-pictures').viewer();
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

function formEditFoto(ID)
{
	$.ajax({
		type: 'POST',
		url: get_detil_poto,
		dataType: "JSON",
		data: 'hID=' + ID,
		success: function(html) {

			setTimeout(function() {
				$.unblockUI({
					onUnblock: function() {
						if (html.error === '')
						{
							$('#formModal6').modal('show');
							$('#idgambar').val(html.id);
							$('#txtketerangan').val(html.keterangan);
							$('#foto-rekam-medis').attr('src', link_load_foto + '/' + html.gambar);
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

function formDeleteFoto(ID)
{
	var konfirm = confirm('Foto Rekam Medis Pasien akan di hapus ?');
		
	if (konfirm == 1) {
		$.ajax({
			type: 'POST',
			url: act_delete_poto,
			dataType: "JSON",
			data: 'hID=' + ID,
			success: function(html) {

				setTimeout(function() {
					$.unblockUI({
						onUnblock: function() {
							if (html.error == "")
							{
								load_galeri();
								pesanOK(html.msg);
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
}

function get_status_pemeriksaan()
{
	if ( id_status_antrian == '33' ) {
		$('#chkstatus').attr('checked', true);
	} else {
		$('#chkstatus').attr('checked', false);
	}
}
