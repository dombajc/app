<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Client Side Pagination in DataGrid - jQuery EasyUI Demo</title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('plugins/easyui/metro/easyui.css') ?>">
    <script src="<?= base_url('plugins/jQuery/jQuery-2.1.4.min.js') ?>" type="text/javascript"></script>
    <script src="<?= base_url('plugins/easyui/jquery.easyui.min.js') ?>" type="text/javascript"></script>
    <style type="text/css">
    	@font-face {
			font-family: basicFont;
			src: url('<?= base_url()."/fonts/droid/DroidSerif-Regular.ttf"; ?>');
		}
		body, .sidebar .sidebar-menu .treeview-menu > li > a {
			font-family:basicFont !important;
			font-size : 12px !important;
		}
    </style>
</head>
<body>
    <table id="grid"></table>
<script>
	$(function(){
		$('#grid').datagrid({
            url: '<?= base_url("grid_pegawai") ?>',
            singleSelect : true,
            scrollbarSize : 0,
            collapsible : false,
            method : 'post',
            pagination: true,
            pageSize:10,
            loadMsg: 'proses tampil data ...',
            //showFooter:false,
            rownumbers:true,
            fitColumns: false,
            multiSort:true,
            //remoteFilter:true,
            //view:scrollview,
            //pageSize:1,
            pageList:[10,50,100],
            columns: [[
                    {title: 'NAMA', field: 'nama_pegawai', halign: 'center',sortable:true},
                    {title: 'NIP', field: 'nip', halign: 'center',sortable:true},
                    {title: 'STATUS', field: 'status_pegawai', halign: 'center',sortable:true}
                    //{title: 'AKTIF', field: 'publish', align: 'center', formatter: validasi, halign: 'center', width: 45}
                ]],
            onBeforeLoad: function(param) {
                //param.nama = $('#filternama').val();
            }
        });
	});
</script>
</body>
</html>


