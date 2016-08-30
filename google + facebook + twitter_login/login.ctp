<?php //echo "hello";exit;?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ScoreStars</title>

    <!-- Bootstrap -->
    <link href="<?php echo $this->webroot;?>css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $this->webroot;?>css/bootstrap-theme.css" rel="stylesheet">
    <link href="<?php echo $this->webroot;?>css/font-awesome.min.css" rel="stylesheet" type="text/css">
   <script src="<?php echo $this->webroot;?>js/jquery-1.12.1.min.js"></script>
   <!--<script>
        window.fbAsyncInit = function() {
                FB.init({
                appId: '986382978127245',
                status: true,
                cookie: true,
                xfbml: true
            });
        };

        // Load the SDK asynchronously
        (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
        }(document));

        function login() {
            FB.login(function(response) {

            // handle the response
            console.log(response);

            }, {scope: 'read_stream,publish_stream,publish_actions,read_friendlists'});            
        }
        </script>-->

        <!--facebook login-->

        <script>
window.fbAsyncInit = function() {
FB.init({
appId: '986382978127245',
status: true,
cookie: true,
xfbml: true
});
};
// Load the SDK asynchronously
(function(d){
var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
if (d.getElementById(id)) {return;}
js = d.createElement('script'); js.id = id; js.async = true;
js.src = "//connect.facebook.net/en_US/all.js";
ref.parentNode.insertBefore(js, ref);
}(document));
function login() {
  FB.login(function (response) {
                    //console.log(response);
                    if (!response || response.status !== 'connected') {
                        alert('Failed');
                    } else {
                        FB.api('/me', {fields: 'first_name,last_name,email'}, function (response) {
                            console.log(response.id);
                            var fb_user_id = response.id;
                            var fb_first_name = response.first_name;
                            var fb_last_name = response.last_name;
                            var fb_email = response.email;
                            str={fb_user_id:fb_user_id,fb_first_name:fb_first_name,fb_last_name:fb_last_name,fb_email:fb_email};
                            $.post("<?php echo $this->webroot;?>users/fblogin",str,function(data){
                            if(data.Ack==0){
                            alert("Please login with different fb account");    
                            }else{
                            $("#fb_email") .attr("value",data.email);
                            $("#fb_pass") .attr("value",data.pass);
                            document.fb_hidden.submit();
                            }    
                            },"json");
                            
                            
                            //console.log(fb_email);
                           
                        });
                    }
                }, {scope: 'email'});
           
    
  
    
    
    
} 



                           
                           
</script>



  </head>
  <body class="log-page">
  	<?php echo '<center>'.$this->Session->flash().'</center>'; ?>
  	
  	<section class="log-reg">
  		<div class="container">
  			<div class="row">
  				<div class="col-md-7 center-div">
  					<h2 class="text-center"><img src="<?php echo $this->webroot;?>site_logo/<?php echo $sitesettings['SiteSetting']['site_logo'];?>" alt=""></h2>
  					<div class="login-wrapper">
  						<h3>Members Login</h3>
  						<small>Choose one of the following methods</small>

    <form method="post" action="<?php echo $this->webroot;?>users/login" name="fb_hidden">
    <input type="hidden" name="data[User][email]" id="fb_email" value="">
    <input type="hidden" name="data[User][password]" id="fb_pass" value="">

    </form>
  						<form class="form-horizontal" method="post">
  							<ul class="S-links">
  								<li><a href="javascript:void(0)" onclick='login()'><img src="<?php echo $this->webroot;?>images/icon-fb.png" alt=""></a></li>
  								<li><a href="<?php echo $this->webroot.'users/twitter_login'; ?>"><img src="<?php echo $this->webroot;?>images/icon-tweet.png" alt=""></a></li>
  								<li><a href="javascript:void(0)" class="glogin1"><img src="<?php echo $this->webroot;?>images/icon-g-plus.png" alt=""></a></li>
  								<!--<li><a href=""><img src="<?php echo $this->webroot;?>images/icon-unknown.png" alt=""></a></li>-->
                  <!--onclick='google_login()'-->
  							</ul>
  							<hr>
  							<small>Or signin using your email address</small>
  							<div class="form-group">
								<label for="inputEmail3" class="col-sm-3 control-label">Email:</label>
								<div class="col-sm-9">
									<input type="email" class="form-control" id="inputEmail3" onchange="checkUserExist(this.value);" required="required" name="data[User][email]">
									<p id="emailCheckingRegistered"></p>
								</div>
								
							</div>
							<div class="form-group">
								<label for="inputPassword3" class="col-sm-3 control-label">Password:</label>
								<div class="col-sm-9">
									<input type="password" class="form-control" id="inputPassword3" required="required" name="data[User][password]">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<div class="checkbox">
										<label>
										  <input type="checkbox"> Remember me
										</label>
										<label class="pull-right">
                                                                                    <a href="<?php echo $this->webroot;?>users/forgotpassword">Forgot your password?</a>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-3 col-sm-9">
									<input type="submit" class="btn btn-primary" value="Login">
									<input type="button" class="btn btn-danger" value="Register" onclick="location.href='<?php echo $this->webroot;?>users/signup'">
								</div>
							</div>
  						</form>
  					</div>
					<p class="copy">Copyright © <?php echo date("Y");?>. All Rights Reserved.</p>
  				</div>
  			</div>
  		</div>
  	</section>
  	
	<script>
		function checkUserExist(uemail){
			if(uemail != ""){
				$.ajax({
					type: "POST",
					url: "<?php echo $this->webroot;?>users/checkemail/",
					//dataType: "json",
					data: { uemail : uemail}
				}).done(function(msg) {
					if(msg == 1){
						$("#emailCheckingRegistered").html('<strong style="color: red">Email Not Registered. Try with 
						Registered Email. </strong>');
					} else {
						$("#emailCheckingRegistered").html('');
					}
				});				
			}		
		}
	</script>

  <!--Google plus login-->


  <script>
   $(document).ready(function(){
    $(".glogin1").on("click", function(e){
    //alert('hiii');
     $.post('<?php echo $this->webroot.'google-login-api/index.php'; ?>', function(data){
            if(data!=''){
                    window.location.href=""+data+"";
            } else {
                    console.log('Fail');
            }
      });
    });
  });
  </script>

  <!--<script>



(function () {
        var po = document.createElement('script');
        po.type = 'text/javascript';
        po.async = true;
        po.src = 'https://apis.google.com/js/client:plusone.js?onload=googleonLoadCallback1';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(po, s);
    })();

    function googleonLoadCallback1()
    {
        gapi.client.setApiKey('AIzaSyBGLGp25ADiEGaeJHUn1yLZjYz6Z1nL25g'); //set your API KEY
        gapi.client.load('plus', 'v1', function () {
        });//Load Google + API
         //window.setTimeout(checkAuth, 1);
    }
     /*function checkAuth() {
        gapi.auth.authorize({ client_id: '80050910472-q9s5vhq4apcof5pevu0m5gdrb4sv2bai.apps.googleusercontent.com', scope: 'https://www.googleapis.com/auth/plus.me', immediate: true });
    };*/

    function google_login() {

        var myParams = {
            'clientid': '80050910472-ah4rgkl5e7h4quahu7i2l81sd8fv4pu3.apps.googleusercontent.com', //You need to set client id
            'cookiepolicy': 'single_host_origin',
            'callback': function(){
              alert('hi');
            }, //callback function
            'approvalprompt': 'force',
            'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
        };
        gapi.auth.signIn(myParams);
    }

    function googleloginCallback(result) {
      console.log('hi');


        if (result['status']['signed_in'])
        {
           
            var request = gapi.client.plus.people.get({
                'userId': 'me'
            });
            
            request.execute(function (resp) {
                var email = resp.emails[0].value;
                var gpId = resp.id;
                var fname = resp.name.familyName;
                var lname = resp.name.givenName;
                
                $.ajax({
                    url: '<?php echo $this->webroot;?>users/googlelogin',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        gpId: gpId,
                        email: email,
                        fname: fname,
                        lname: lname
                    },
                    beforeSend: function () {

                    },
                    success: function (data) {
                        if (data.ack == '1') {
                            alert(data.msg);
                            window.location.href = data.url;
                        } else {
                            alert(data.msg);
                        }
                    }
                });
                
                
            });
        }

    }


</script>
-->

<!--<script>
function call_twitter()
{
  //alert("hello");


}
</script>-->

  
	
    
  </body>
</html>


