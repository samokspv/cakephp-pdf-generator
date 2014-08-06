<div class="pdf-table">
	<div class="pdf-tr">
		<div class="pdf-td pdf-left">
			<img src="<?php echo App::pluginPath('PdfGenerator') . 'webroot/images/pdf/DOCUMENTS_Report_Parts_Note_Footer.svg'?>">
		</div>
		<div class="pdf-td pdf-right">
			<?php echo $this->requestAction('/PdfGenerator/getPageNumber', array('return')); ?>
		</div>
	</div>
</div>