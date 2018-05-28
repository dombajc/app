<!-- CSS Bootgrid  -->
<link href="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.css') ?>" rel="stylesheet" />

<div class="row">
  <div class="col-lg-12">
    <section class="panel">
      <header class="panel-heading">
        </i> <?= $title ?>
      </header>
      <div class="panel-body">
        
        <div class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><strong>Pencarian Data Cepat :</strong></label>
            <label class="col-sm-2 control-label">Nama Pegawai</label>
            <div class="col-sm-2">
              <input type="text" id="find-nama" class="form-control">
            </div>
            <label class="col-sm-1 control-label">No.SK</label>
            <div class="col-sm-2">
              <input type="text" id="find-no-sk" class="form-control">
            </div>
            
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">Lokasi Tujuan</label>
            <div class="col-sm-5">
              <select id="find-lokasi" class="form-control">
                <?= $this->Lokasi->pilihanLokasiHomebase() ?>
              </select>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-info btn-block" id="btn-cari"> Cari Data </button>
        <br>
        <table id="grid" class="table table-condensed table-bordered table-hover">
          <thead>
            <tr>
              <th data-column-id="nama_pegawai" data-header-align="center">Nama Pegawai</th>
              <th data-column-id="lokasi_asal" data-header-align="center" data-align="center">Lokasi Asal</th>
              <th data-column-id="jenis_mutasi" data-header-align="center" data-align="center">Status</th>
              <th data-column-id="nama_homebase" data-header-align="center" data-align="center">MUtasi Tujuan</th>
              <th data-column-id="no_sk" data-header-align="center" data-align="center">No.SK</th>
              <th data-column-id="tgl_sk" data-header-align="center" data-align="center">TMT</th>
              <th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="60px">OPSI</th>
            </tr>
          </thead>
        </table>
      </div>
    </section>
  </div>
</div>

<!-- Boot Grid -->
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.js') ?>"></script>
<script src="<?= base_url('plugins/jquery-bootgrid-master/dist/jquery.bootgrid.fa.js') ?>"></script>


<script>
  $(function(){
    $('#btn-cari').click(function(){
      $('#grid').bootgrid('reload');
    });

    $("#grid").bootgrid({
      ajax: true,
      post: function ()
      {
        /* To accumulate custom parameter with the request object */
        return {
          postLokasi : $('#find-lokasi').val(),
          postNoSK : $('#find-no-sk').val(),
          postNama : $('#find-nama').val()
        };
      },
      url: "<?= base_url('load_data_mutasi_masuk') ?>",
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
          if ( row.aktif == 1 ){
            var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-green\" onClick=\"aksivalid('" + row.id_mutasi + "',0)\"><span class=\"fa fa-check\"></span></button> ";
          } else {
            var showButton = "<button type=\"button\" class=\"btn btn-xs btn-default text-red\" onClick=\"aksivalid('" + row.id_mutasi + "',1)\"><span class=\"fa fa-ban\"></span></button> ";
          }
          
          return showButton;
        }
      }
    });

  });

  function aksivalid(id, valid) {
      $.ajax({
          type: 'POST',
          url: '<?= base_url("set_aktif_mutasi") ?>',
          dataType: "JSON",
          data: 'haksi=validasi&hID=' + id + '&value=' + valid,
          success: function(html) {

              setTimeout(function() {
                  $.unblockUI({
                      onUnblock: function() {
                          if (html.error == "")
                          {
                              $('#grid').bootgrid('reload');
                              pesanOk(html.msg);
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
  
</script>