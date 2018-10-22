<form method="post">
		
	<label>Student's Name</label><input required="" type="text" value="<?php if($result){echo $result[0]->name; } ?>" name="name"> <br>
	
	<label>Student's Address</label><input required="" type="text" value="<?php if($result){echo $result[0]->address; } ?>" name="address"> <br>
	
	<label>Student's Email</label><input required="" type="email" value="<?php if($result){echo $result[0]->email; } ?>" name="email"> <br>
	
	<label>Student's Phone</label><input required="" type="number" value="<?php if($result){echo $result[0]->phone; } ?>" name="phone"> <br>
	
	<label>Student's Optional Description</label>
	<textarea name="description"><?php if($result){echo $result[0]->description; } ?></textarea> <br>
	
	<?php if($result){ echo '<input type="hidden" name="id" value="'.$result[0]->id.'">';} ?>

	<button class="crud-btn" <?php if($result){echo "disabled";} ?> name="add_student"><?php if($result){echo "Edit"; }else{echo"Save";} ?></button>
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