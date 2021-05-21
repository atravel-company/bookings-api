function link(id,role) {
	var user=$('#role'+id).val();
	assetBaseUrl = "https://atsportugal.com/public/";
	var adress=assetBaseUrl+'groups/linka'+'/'+role+'/'+user;
    window.location.href=adress
}

function delink(id,role) {
	var user=id;
	assetBaseUrl = "https://atsportugal.com/public/";
	var adress=assetBaseUrl+'groups/delinka'+'/'+role+'/'+user;
    window.location.href=adress
}