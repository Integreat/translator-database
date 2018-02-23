<h3 class="text-center"><?php echo $lang['de']['click_contact']; ?></h3>
<div id="language-selection">
	
	<form id="language-form">
		<?php
			echo generateLanguageSelect($sqlcon,$lang[$locale]['filter_languages'],"languages",0,"update_list();"). " ";
			echo generateTimeslotSelect($sqlcon,$lang[$locale]['filter_timeslots'],"timeslots",0,"update_list();")
		?>
	</form>
	<script>
		function update_list() {

			var action = "ajax.php?linkid=7",
				method = "POST",
				data   = $("#language-form").serialize();
				
			$.ajax({
				url : action,
				type : method,
				data : data
			}).done(function (data) {
				$("#interpreter-content").html(data);
			});
		};
	</script>
</div>
<div id="interpreter-content">
	<?php include("list-table.php"); ?>
</div>
