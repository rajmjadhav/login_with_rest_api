<?php
  if(!isset($_GET['user_id']) || $_GET['user_id']==''){
	  header("location:login.php");exit;
  }
?>
<!DOCTYPE html>
<html>
<body>

<style>
div.bottom
        {
            height: 30px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
            margin-top: none;
            margin-bottom: none;
            border-top: 4px solid #00ccff;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
           
        }
</style>
<h1>Work List</h1>
<script type = "text/javascript" 
         src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		 <script>
		 function update_score(work_id,score){
			 $.ajax({
								url: "http://localhost:8080/login_with_rest_api/rest/update_score/",
								type: "Post",
								data: { work_id : work_id,score:score},
								
								success: function (data,txt_status,xhr) { 
										location.reload();								
									
								},
								error: function () {  }
							});
		 }
		       $(document).ready(function () {
						
							var user_id = $("#user_id").val();
														
							$.ajax({
								url: "http://localhost:8080/login_with_rest_api/rest/work_list/",
								type: "Post",
								data: { user_id : user_id},
								
								success: function (data,txt_status,xhr) { 
									var objJSON = jQuery.parseJSON(JSON.stringify(data));
									var work_data='';
									for (var i = 0, len = objJSON.length; i < len; ++i) {
                                       var json_work = objJSON[i];
									   work_data=work_data+
									   '<div class="bottom">'+
                                            '<div style="float: left; width: 33%;">'+json_work.work_name+'</div>'+
                                            '<div style="float: left; width: 33%;">'+json_work.work_score+'</div>'+
                                            '<div style="float: left; width: 33%;"><button onclick="return update_score('+json_work.work_id+',10)">10</button> / <button onclick="return update_score('+json_work.work_id+',0)">0</button>'+'</div>'+
                                        '</div>';
									} 
                                    $("#div_work_list").append(work_data);									
									
								},
								error: function () {  }
							});return false; 
												
				}); 
		 </script>

<input type="hidden" name="user_id" id="user_id" value="<?php echo addslashes($_GET['user_id']); ?>">
<div id="div_work_list">
<div class="bottom">
  <div style="float: left; width: 33%;"><b>Work</b></div>
  <div style="float: left; width: 33%;"><b>Work Score</a></div>
  <div style="float: left; width: 33%;"><b>Update Work Score</a></div>
</div>
</div>


</body>
</html>