<?php 
echo $this->Html->css('PdfGenerator.generate-pdf-link');
echo $this->Html->script('PdfGenerator.pdf');
?>
<div class="generate-pdf-link">
	<?php
	echo $this->Html->link('Generate Pdf', 'javascript:void(0)', array(
		'class' => 'btn',
		'onclick' => 'PdfGeneratorTask.add()'
	));
	?>
	<div style="display: none;" id="pdfLoadingLed">
		<div class="status"></div>
	</div>
</div>