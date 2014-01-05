<?php include('header.php'); ?>
	<script type="text/javascript">
		function showError(error) {
			$('#error_title').html(error);
			$('#copy_label').html('Copy It');
			$('#url_input').val('Some popular URL').toggleClass('dumb').blur();
			$('#link_response').hide();
			$('#error_div').fadeIn(300);
			setTimeout(function(){$('#error_div').fadeOut(1000);}, 2000);
		}
		
		function showlink(hash) {
			$('#link_text').html('<?php echo BASE_URL; ?>l/' + hash);
			$('#copy_label').html('Copy It');
			$('#url_input').val('Some popular URL').toggleClass('dumb').blur();
			$('#error_div').hide();
			$('#link_response').fadeIn(300);
		}
	
		$(function() {
			$('#copy_button').clipboard({
		        path: '<?php echo BASE_URL; ?>static/jquery.clipboard.swf',
		        copy: function() {
		            //alert('Copied!');
		            // Hide "Copy" and show "Copied, copy again?" message in link
		            $('#copy_label').html('Copy Again');
		            $('#copy_button').stop().css("background-color", "#CCEBFF")
					.animate({ backgroundColor: "#0099FF"}, 200);;
		
		            // Return text in closest element (useful when you have multiple boxes that can be copied)
		            return $('#link_text').html();
		        }
		    });
			
			$('#url_input').focusin(function(){
				if ($(this).val() == 'Some popular URL') $(this).val('').toggleClass('dumb');
			}).focusout(function(){
				if ($(this).val() == '') $(this).val('Some popular URL').toggleClass('dumb');
			});
			
			$('#url_input').keypress(function(event){
			    if(event.keyCode == 13){
			    	event.preventDefault();
			        $("#url_button").click();
			    }
			});
			
			$('#new_button').click(function(){
				$('#link_response').fadeOut(300);
			});
		    
			$('#url_button').click(function(){
				//do some checks to see if we have data in there first
				var URI = $('#url_input').val();
				
				if (URI == '' || URI == 'Some popular URL') {
					showError('Please find something on the internet to bury.');
					return true;
				}
				
				var  urlData = "url=" + URI;  //Name value Pair
				//send out the request
				$.ajax({
				    url : "add",
				    type: "POST",
				    data : urlData,
				    dataType: 'json',
				    success: function(data, textStatus, jqXHR)
				    {
				    	if (data.message) showError(data.message);
				        else showlink(data.hash);
				    },
				    error: function (data, textStatus, errorThrown)
				    {
				 		showError(data.message);
				    }
				});
			});
			$('#link_response').hide();
			$('#error_div').hide();
			
			setTimeout(function(){$('#logo_white').fadeIn(400);}, 800);
			setTimeout(function(){$('#logo_blue').fadeIn(400);}, 1100);
			setTimeout(function(){$('#center').fadeIn(400);}, 500);
		});
	</script>
	<p id="logo_white">Mnstrm.</p><p id="logo_blue">me</p>
    <div id="center">
    	<form method="post" action="">
    		<label id="url_label">Enter your mainstream URL...</label>
    		<input type="text" id="url_input" class="dumb" value="Some popular URL">
    	</form>
		<div id="url_button">
			<label>Bury It</label>
		</div>
    </div>
    <div id="link_response">
    	<label id="link_title">Your link is now underground!</label>
    	<label id="link_title2">Share it at:</label>
    	<label id="link_text">Something went wrong :(</label>
    	<div id="copy_button">
    		<label id="copy_label">Copy It</label>
    	</div>
    	<div id="new_button">
    		<label id="new_label">Bury Another</label>
    	</div>
    </div>
    <div id="error_div">
    	<font id="frown">:(</font>
    	<label id="error_title">Something went wrong :(</label><br>
    </div>

<?php include('footer.php'); ?>