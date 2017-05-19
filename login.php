<!DOCTYPE html>
<html>
<body>

<h1>Login</h1>
<script type = "text/javascript" 
         src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		 <script>
		       $(document).ready(function () {
						$("#submit").click(function () { 
							var user_name = $("#user_name").val();
							var pwd = $("#user_pass").val();
							
							$.ajax({
								url: "http://localhost:8080/login_with_rest_api/rest/login/",
								type: "Post",
								data: { user_name : user_name, pwd : pwd },
								
								success: function (data,txt_status,xhr) { 
									var yourval = jQuery.parseJSON(JSON.stringify(data));
									
									if(xhr.status==200){
										
										window.location="work.php?user_id="+yourval.user_id;
									}else{
										alert("Wrong user name and password");
									}
								},
								error: function () {  }
							});return false; 
						});
						
				}); 
		 </script>
<form name="login" id="login" action="">
<p>User Name:<input type="text" name="user_name" id="user_name"></p>
<p>Password:<input type="password" name="user_pass" id="user_pass"></p>
<p><input id="submit" type="submit"></p>
</form>
</body>
</html>