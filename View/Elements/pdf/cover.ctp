<?php $curl = (!empty($pdf['curl']) ? $pdf['curl'] : ''); ?>
<div class="pdf-table pdf-big">
	<div class="pdf-tr">
		<div class="pdf-td pdf-header">
		</div>
	</div>
	<div class="pdf-tr">
		<div class="pdf-td pdf-body" style="height:800px">
			<div class="pdf-table pdf-cover">
				<div class="pdf-tr">
					<div class="pdf-td pdf-center">
						<a href="<?php echo $curl ?>">
							заменить<img src="<?php echo WWW_ROOT . 'images/pdf/SNATZ_DM_Report_Parts_Logo_Cover.svg' ?>">
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pdf-tr">
		<div class="pdf-td pdf-page-name" style="height:150px;">
			<?php echo $this->element('pdf/page_name', array(
				'text' => $pdf['date'],
				'class' => 'pdf-center'
			)); ?>
			<div class="pdf-table" style="margin-top:20px">
				<div class="pdf-tr">
					<div class="pdf-td pdf-center">
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pdf-tr">
		<div class="pdf-td pdf-footer">
		</div>
	</div>
</div>