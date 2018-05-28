<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				<?= $title ?>
				<button type="button" class="btn btn-xs btn-info pull-right" id="btn-tambah" disabled> <i class="glyphicon glyphicon-plus-sign"></i> </button>
			</header>
			<div class="panel-body">
				<table id="grid" class="table table-condensed table-bordered table-hover">
					<thead>
						<tr>
							<th data-column-id="lokasi" data-header-align="center">Lokasi</th>
							<th data-column-id="" data-formatter="commands" data-header-align="center" data-sortable="false" data-align="center" data-width="90px">OPSI</th>
						</tr>
					</thead>
				</table>
			</div>
		</section>
	</div>
</div>