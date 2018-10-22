
<form method="post">
	
	<label>Subject's Name</label><input required="" type="text" name="name" value="<?php if($result){echo $result[0]->name; } ?>"> <br>
	<label>Subject's Code</label><input required="" type="text" name="code" value="<?php if($result){echo $result[0]->code; } ?>"> <br>
	<label>Subject's Teacher</label>
	<select type="text" name="teacher">
		<option value="">No Teacher Selected</option>

		<?php foreach ($teachers as $teacher) { ?>
			<option <?php if($result && $teacher->id == $result[0]->teacher_id){echo "selected";} ?> value="<?php echo $teacher->id ?>"> <?php echo $teacher->name ?></option>
		<?php } ?>
	</select> <br>
	<?php if($result){ echo '<input type="hidden" name="id" value="'.$result[0]->id.'">';} ?>
	<button class="crud-btn" name="add_subject">
		<?php if($result){echo "Edit"; }else{echo"Save";} ?>
	</button>
</form>

<?php if($result):  ?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

			$('input, textarea').on('input',function(){
				$('button').removeAttr('disabled');
			});
		});
	</script>
<?php endif ?>

