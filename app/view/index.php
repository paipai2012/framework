<?php
$form = new Pform($category);
$form->errorSummary();
?>
<form id="yw0" action="/myFramework/index.php" method="post">
	<div class="row">

		<?php  $form->labelEx('name');   ?>			<?php  $form->textField('name');   ?> <?php  $form->msg('name');   ?></div>

	<div class="row">
		<?php  $form->labelEx('show');   ?>  <?php  $form->textField('show');   ?>	<?php  $form->msg('show');   ?></div>
	
	<div class="row submit">

		<input type="submit" name="yt1" value="Submit" />	</div>
</form>
