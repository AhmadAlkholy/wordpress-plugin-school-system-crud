<div class="limiter">
	<div class="container-table100">

		<?php if(is_admin()): ?>
			<a href="<?php echo $add_url ?>"><h2> Add New <?php echo  substr($slug, 0, -1)?> </h2></a>
		<?php endif ?>
		<div class="wrap-table100">

			<div class="table100 ver2 m-b-110">
				<table data-vertable="ver2">
					<thead>
						<tr class="row100 head">
							<th class="column100 column1" data-column="column1">Subject's Name</th>
							<th class="column100 column2" data-column="column2">Subject's Code</th>
							<th class="column100 column3" data-column="column3">Teacher's Name</th>
							<?php if(is_admin()): ?>
								<th class="column100 column6" data-column="column6"></th>
								<th class="column100 column7" data-column="column7"></th>
							<?php endif?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($results as $row){  ?>
						<tr class="row100" id="<?php echo "row".$row->id ?>">
							<td class="column100 column1" data-column="column1"><?php echo $row->name ?></td>
							<td class="column100 column2" data-column="column2"><?php echo $row->code ?></td>
							<td class="column100 column3" data-column="column3">
								<?php 
								if ($row->teacher_id) {
									$results2 = $wpdb->get_results( " SELECT name FROM ".$wpdb->prefix."teachers WHERE id = $row->teacher_id" ); echo $results2[0]->name;
								}else{echo "-";}?>
									
								</td>

							<?php if(is_admin()): ?>
							<td class="column100 column6" data-column="column6">
								<a href="<?php echo admin_url( "admin.php?page=new_$cell&id=$row->id" ) ?>">
									<button class="crud-btn edit-btn">Edit</button>
								</a></td>

							<td class="column100 column7" data-column="column7"><button class="crud-btn delete-btn" data-action="delete" data-target="<?php echo $row->id ?>">Delete</button></td>
							<?php endif ?>

						</tr>

						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>