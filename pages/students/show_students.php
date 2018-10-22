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
							<th class="column100 column1" data-column="column1">Student's Name</th>
							<th class="column100 column2" data-column="column2">Student's Address</th>
							<th class="column100 column3" data-column="column3">Student's Email</th>
							<th class="column100 column4" data-column="column4">Student's Phone</th>
							<th class="column100 column5" data-column="column5">Short Description</th>
							<?php if(is_admin()): ?>
								<th class="column100 column6" data-column="column6"></th>
								<th class="column100 column7" data-column="column7"></th>
							<?php endif ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($results as $row){  ?>
						<tr class="row100" id="<?php echo "row".$row->id ?>">
							<td class="column100 column1" data-column="column1"><?php echo $row->name ?></td>
							<td class="column100 column2" data-column="column2"><?php echo $row->address ?></td>
							<td class="column100 column3" data-column="column3"><?php echo $row->email ?></td>
							<td class="column100 column4" data-column="column4"><?php echo $row->phone ?></td>
							<td class="column100 column5" data-column="column5"><?php echo $row->description ?></td>
							<?php if(is_admin()): ?>
							<td class="column100 column6" data-column="column6">
								<a href="<?php echo admin_url( "admin.php?page=new_$cell&id=$row->id" ) ?>">
									<button class="crud-btn edit-btn" data-action="edit" data-target="<?php echo $row->id ?>">Edit</button>
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