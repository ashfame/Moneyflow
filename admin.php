<?php

add_action( 'wp_dashboard_setup', 'mf_dashboard_widgets' );

function mf_dashboard_widgets() {
	wp_add_dashboard_widget( 'money_flow_widget', 'Money Flow', 'mf_dashboard_widget_content' );
}

function mf_dashboard_widget_content() {

	global $current_user;
	get_currentuserinfo();

	if ( isset( $_POST['moneyflow'] ) ) {

		foreach( $_POST['moneyflow'] as $type => $records ) {

			foreach( $records as $person ) {
				// Sanitization of name
				if ( ! ctype_alnum( str_replace( ' ', '', $person['name'] ) ) )
					$person['name'] = 'Dummy';
				// Sanitization of amount
				$person['amount'] = absint( $person['amount'] );

				if ( $person['amount'] != 0 )
					$valid[$type][] = array( 'name' => $person['name'], 'amount' => $person['amount'] );
			}

		}

		update_user_meta( $current_user->ID, 'moneyflow_data', $valid );

		?>
		<script>
			(function($){
				$(document).ready(function(){
					// show saved message
					$('#money_flow_widget h3 span').html('Money Flow <span class="red">Saved!</span>');
					// hide message after timeout
					setTimeout(function(){
						$('#money_flow_widget h3 span').html('Money Flow');
					},2000);
				});
			})(jQuery);
		</script>
		<?php
	}

	$moneyflow = get_user_meta( $current_user->ID, 'moneyflow_data', true );

?>
	<style type="text/css">
		#money_flow_widget table { margin:0; padding:0; }
		#money_flow_widget .widefat td, #money_flow_widget .widefat th { padding:5px 10px 3px; color:#8F8F8F; font-size:15px; border-bottom-color:#FFF; vertical-align:baseline; }
		#money_flow_widget input[type=submit] { margin:0 0 7px 10px; }
		#money_flow_widget input[type=text] { font-size:13px; }
		#money_flow_widget span.red {color:#c30304;}
	</style>
	<script type="text/javascript">
		(function($){
			$(document).ready(function(){
				var money_flow_widget = $('#money_flow_widget');
				// Hide submit button initially
				money_flow_widget.find('.submit-row').hide();

				// As soon as any field changes, show the submit button
				money_flow_widget.find('.small-text').change(function(){
					money_flow_widget.find('.submit-row').slideDown('slow');
				});

				// Add button calls
				money_flow_widget.find('thead a').click(function(){
					var type = $(this).parent().attr('data-type');
					var count = money_flow_widget.find('.take-col tr').length + 1;
					money_flow_widget.find('.'+type+'-col table').append('<tr data-count="'+count+'"><td><p>Ashfame</p><input type="hidden" name="moneyflow['+type+']['+parseInt(count)+'][name]" value="Ashfame" /></td><td><input class="small-text" type="text" name="moneyflow['+type+']['+parseInt(count)+'][amount]" value="1230" /></td></tr>');
					money_flow_widget.find('.submit-row').slideDown('slow');
					return false;
				});

				// Make name editable
				money_flow_widget.find('p').click(function(){
					var p = $(this);
					var nameField = p.next();
					var name = $(nameField).val();
					// hide <p>
					p.hide();
					// insert a input field to modify name
					p.after('<input type="text" class="name-edit" value="'+name+'" />');
					// cache temp field
					var tempField = p.next('.name-edit');
					// set focus
					tempField.focus();
					// save on blur
					tempField.blur(function(){
						// save name to hidden field
						nameField.val( tempField.val() );
						// remove temp fiels
						tempField.remove();
						// show new value in <p>
						p.html( tempField.val() );
						// make <p> visible
						p.show();
						// show submit button
						money_flow_widget.find('.submit-row').slideDown('slow');
					});

				});

			});
		})(jQuery);
	</script>

	<form action="" method="POST">
	<table id="moneyflow" class="widefat">
		<thead>
			<tr>
				<th data-type="take">Take Money (<a href="#">Add</a>)</th>
				<th data-type="give">Give Money (<a href="#">Add</a>)</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="take-col">
					<table>
					<?php foreach ( $moneyflow['take'] as $key => $person ) { ?>
							<tr data-count="<?php echo $key; ?>">
								<td>
									<p><?php echo $person['name']; ?></p>
									<input type="hidden" name="moneyflow[take][<?php echo $key; ?>][name]" value="<?php echo $person['name']; ?>" />
								</td>
								<td>
									<input class="small-text" type="text" name="moneyflow[take][<?php echo $key; ?>][amount]" value="<?php echo $person['amount']; ?>" />
								</td>
							</tr>
					<?php }	?>
					</table>
				</td>
				<td class="give-col">
					<table>
					<?php foreach ( $moneyflow['give'] as $key => $person ) { ?>
							<tr data-count="<?php echo $key; ?>">
								<td>
									<p><?php echo $person['name']; ?></p>
									<input type="hidden" name="moneyflow[give][<?php echo $key; ?>][name]" value="<?php echo $person['name']; ?>" />
								</td>
								<td>
									<input class="small-text" type="text" name="moneyflow[give][<?php echo $key; ?>][amount]" value="<?php echo $person['amount']; ?>" />
								</td>
							</tr>
					<?php }	?>
					</table>
				</td>
			</tr>
			<tr class="submit-row">
				<td><input class="button-primary" type="submit" value="Submit" /></td>
				<td></td>
			</tr>
		</tbody>
	</table>
	</form>

	<?php

}