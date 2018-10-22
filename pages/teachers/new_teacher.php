<div class="school-system-form" style="margin:0 auto">
	<form method="post">

		<label style="">Teacher's Name</label>
		
		<input style="" required="" type="text" value="<?php if($result){echo $result[0]->name; } ?>" name="name"> 
		
		<label>Teacher's Address</label>
		<input required="" type="text" value="<?php if($result){echo $result[0]->address; } ?>" name="address"> 
		
		<label>Teacher's Email</label>
		<input required="" type="email" value="<?php if($result){echo $result[0]->email; } ?>" name="email"> 
		
		<label>Teacher's Phone</label>
		<input required="" type="text" value="<?php if($result){echo $result[0]->phone; } ?>" name="phone"> 
		
		<label>Teacher's Optional Description</label>

		<textarea name="description"><?php if($result){echo $result[0]->description; } ?></textarea> 
		
		<?php if($result){ echo '<input type="hidden" name="id" value="'.$result[0]->id.'">';} ?>

		<button class="crud-btn" <?php if($result){echo "disabled";}?> name="add_teacher"><?php if($result){echo "Edit"; }else{echo"Save";} ?></button>
	</form>
</div>
	


<?php if($result):  ?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {

			$('input, textarea').on('input',function(){
				$('button').removeAttr('disabled');
			});
		});
	</script>
<?php endif ?>