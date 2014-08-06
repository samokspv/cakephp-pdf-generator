<div class="pdf-table">
	<div class="pdf-tr">
		<div class="pdf-td pdf-left">
			заменить<img src="<?php echo APP ?>webroot/images/pdf/SNATZ_DM_Report_Parts_Note_Footer.svg">
		</div>
		<div class="pdf-td pdf-right">
			<?php echo $this->requestAction('/PdfGenerator/getPageNumber', array('return')); ?>
		</div>
	</div>
</div>