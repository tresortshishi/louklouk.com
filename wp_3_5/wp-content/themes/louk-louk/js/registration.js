/**

action form selection (members/enterperise)
*/
document.getElementById("sign_type").onchange = function(){
	var type = document.getElementById("sign_type").value;
	
	var url_path = location.protocol + '//' + location.host + location.pathname;

	
		if(type == "Membres"){
			url_path = url_path+"/?type=user_sign";
			 document.location.href=url_path; 
		}else{
			
			if(type == "Entreprise"){
			
				url_path = url_path+"/?type=entreprise_sign";
				document.location.href=url_path;
			}
		}
	
}
document.getElementById("sign_type").onchange();