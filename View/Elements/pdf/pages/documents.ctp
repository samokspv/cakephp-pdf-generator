<?php
$index = 1;
if (!empty($documents) && is_array($documents)) {
foreach ($documents as $document) {
?>
<div class="pdf-table pdf-big">
	<div class="pdf-tr">
		<div class="pdf-td pdf-header">
			<?php echo $this->element('PdfGenerator.pdf/pages/header'); ?>
		</div>
	</div>
	<div class="pdf-tr">
		<div class="pdf-td pdf-body">
			<div class="pdf-tr">
				<div class="pdf-td pdf-page-name">
				</div>
			</div>
			<div class="pdf-tr">
				<div class="pdf-td pdf-general-review">
					<span style="float:right;">#<?php echo $index;?></span>
					<?php if (!empty($document['images'][0])) {
						$this->Html->image($document['images'][0], array('class' => 'img-polaroid pull-right'));
						}
					?>
					<div>
						<strong>
							<?php echo $document['title'] ?>
						</strong>
					</div>
					<div>
						<?php echo $this->Text->truncate(
							$document['description'],
							600,
							array('ellipsis' => '...', 'exact' => false)
						);?>
					</div>
					<hr/>
					<strong>
						<?php echo 'Date: ' . $this->Time->format('F d H:i', $document['pubtime']); ?>
					</strong>
					<div>
						<strong>Labels:&nbsp;</strong>
						<?php echo implode(', ', $document['labels']); ?>
					</div>
					<div>
						<strong>Terms:&nbsp;</strong>
						<?php echo implode(', ', $document['terms']); ?>
					</div>
					<div>
						<strong>Summary:&nbsp;</strong>
						<?php echo implode(', ', $document['summary']) ?>
					</div>
					<div>
						<strong>Threads:</strong>
					</div>
					<?php
					$threadsCount = count($document['threads']);
					for ($threadNumber = 0; $threadNumber < $threadsCount; $threadNumber++) { ?>
					<div>
						<strong>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $threadNumber; ?>:&nbsp;</strong>
							<?php echo empty($document['threads'][$threadNumber]) ? '' : implode(', ', $document['threads'][$threadNumber]); ?>
						</div>
					<?php } ?>
					<div>
						<strong>Rank:&nbsp;</strong>
						<?php echo $document['rank']; ?>
					</div>
					<div class="pull-right">
						<strong><?php echo $document['id']; ?></strong>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<div class="pdf-tr">
		<div class="pdf-td pdf-footer">
			<?php echo $this->element('PdfGenerator.pdf/pages/footer'); ?>
		</div>
	</div>
</div>
<? $index++;
}} ?>