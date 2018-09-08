$(document).ready(function(){
	setInterval(function() {
	    $.post("login.php",{opcion:30},function(data){
	      	if(data==true)
	      	{
	      		/*var form=$("<form>").attr({style:"display: hidden",action:"logout.php" method="POST" id="formout"});
	      		var input=$("<input>").attr({type:"hidden",id:"var1",name:"modal",value:"1"})
	      		form.append(input)
	      		form.submit();*/
	      		window.location.href="logout.php?modal=si";
		   	}
	    },"json")
	}, 10000);
})