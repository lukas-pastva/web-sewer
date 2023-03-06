function ajaxDoStrankaClankov(from, limit, section, newTab){
	$('#contentAjax').fadeOut('fast', function() { 
		$.ajax( 
			{url :'../ajax.php?ajaxDoStrankaClankov=1&from='+from+'&limit='+limit+'&section='+section, 
				success : function(data) { 
					$('#contentAjax').html(data); 
					$('#contentAjax').toggle(); 
					var toLeft = true;  
					if(activeTab<=newTab){ 
						toLeft = false;  
					}
					if(toLeft){ 
						$('#contentAjax').css("marginLeft", "675px");
						$('#contentAjax').animate({"marginLeft": "0px"}, "150" ); 
					}else{ 
						$('#contentAjax').css("marginLeft", "-675px"); 
						$('#contentAjax').animate({"marginLeft": "0px"}, "150" ); 
					}
					$(".short_story_table").hover( function(){ 
						$(this).find(".lupa").fadeIn("fast"); 
					},function(){ 
						$(this).find(".lupa").fadeOut("fast"); 
					}); 
				}
				
		});
	});
}

function display(id){
  if(document.getElementById(id)){
  	var display = document.getElementById(id).style.display;
  	if(display=='block'){
  		document.getElementById(id).style.display='none';
  	} else {
  		document.getElementById(id).style.display='block';
  	}		
  }
}

function hide(id){
  if(document.getElementById(id)){
  	document.getElementById(id).style.display='none';
	}
}


function changeColor(id){
	if(id.style.backgroundColor == 'rgb(255, 255, 255)'){
	  id.style.background = 'rgb(247, 246, 244)';
	} else {
	  id.style.background = 'rgb(255, 255, 255)';
	}
}

function play(file){
  var path = getPath();
	if(path == '../'){
	  swfobject.embedSWF(path+'resources/video_player_2.swf?video_source='+file+'', 'video', '236', '200', '9.0.0', path+'resources/expressInstall.swf',{}, {allowfullscreen:'true'});
	} else {
	  swfobject.embedSWF('resources/video_player.swf?video_source='+file+'', 'video', '236', '200', '9.0.0', path+'resources/expressInstall.swf',{}, {allowfullscreen:'true'});
  }
}

function play2(file, id){
  var path = getPath();
	if(path == '../'){
	  swfobject.embedSWF(path+'resources/video_player_big_2.swf?video_source='+file+'', id, '440', '380', '9.0.0', path+'resources/expressInstall.swf',{}, {allowfullscreen:'true'});
	} else {
	  swfobject.embedSWF(path+'resources/video_player_big.swf?video_source='+file+'', id, '440', '380', '9.0.0', 'resources/expressInstall.swf',{}, {allowfullscreen:'true'});
 }
}



var http_request = false;

function createRequest(method, url, parameters, callback) // request
{
	http_request = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
	   	http_request = new XMLHttpRequest();
	   	if (http_request.overrideMimeType) {
	       	http_request.overrideMimeType('text/html');
	   	}
	} else if (window.ActiveXObject) { // IE
	   	try {
	       	http_request = new ActiveXObject("Msxml2.XMLHTTP");
	   	} catch (e) {
	      	try {
		   		http_request = new ActiveXObject("Microsoft.XMLHTTP");
	       	} catch (e) {}
	   	}
	}
	if (!http_request) {
	   	return false;
	}
    				      	
	http_request.onreadystatechange = callback;
	http_request.open(method, url + parameters, true);      
}
function fotografie(){   	
	if (http_request.readyState == 4) {
    if (http_request.status == 200) {
    	document.getElementById('fotoalbumHead').innerHTML='Zoradené'+http_request.responseText;
    }
  }
}
function fotografieZorad(pole){
	createRequest('GET', getPath()+'./admin_ajax.php', '?typ=fotografie_zorad&pole='+pole, function(){fotografie();});
	http_request.send(null);
}


function fotografieVymaz(){   
	if (http_request.readyState == 4) {
    if (http_request.status == 200) {
    	var Xpos = http_request.responseText.indexOf('|');
    	var pictureId = ''+http_request.responseText.substring(0, Xpos);
    	var href= http_request.responseText.substring(Xpos+1);
    	document.getElementById(pictureId).style.display='none';
    	window.location.href = href;    	
    }
  }
}
function doFotografieVymaz(id, structure_id, clanok_id){
	createRequest('GET', getPath()+'./admin_ajax.php', '?typ=fotografie_vymaz&id='+id+'&structure_id='+structure_id+'&clanok_id='+clanok_id, function(){fotografieVymaz();});
	http_request.send(null);
}


function suvisiaciClanok(){   
	if (http_request.readyState == 4) {
    if (http_request.status == 200) {
    	document.getElementById('clanok_suvisiace').innerHTML = http_request.responseText;
    	document.getElementById('clanok_suvisiaci').value='';
    }
  }
}
function doSuvisiaciClanok(clanok_id, clanok_id_suvisiace, typ, clanok_suvisiace_id ){
	createRequest('GET', getPath()+'./admin_ajax.php', '?clanok_id='+clanok_id+'&clanok_id_suvisiace='+clanok_id_suvisiace+'&typ='+typ+'&clanok_suvisiace_id='+clanok_suvisiace_id, function(){suvisiaciClanok();});
	http_request.send(null);
}

function najdiNazovClanku(){   
	if (http_request.readyState == 4) {
    if (http_request.status == 200) {
    	document.getElementById('nasepkavac').innerHTML = http_request.responseText;
    	document.getElementById('nasepkavac').style.display = 'block';
    }
  }
}
function doNajdiNazovClanku(text, clanok_id){
	createRequest('GET', getPath()+'./admin_ajax.php', '?text='+text+'&typ=clanok_suvisiace_search'+'&clanok_id='+clanok_id, function(){najdiNazovClanku();});
	http_request.send(null);
}
function zdrojClanok(){   
	if (http_request.readyState == 4) {
    if (http_request.status == 200) {
    	document.getElementById('clanok_zdroj').innerHTML = http_request.responseText;
    	document.getElementById('clanok_zdroj_input').value='';
    }
  }
}
function doZdrojClanok(clanok_id, zdroj, typ ){
	createRequest('GET', getPath()+'./admin_ajax.php', '?clanok_id='+clanok_id+'&zdroj='+zdroj+'&typ='+typ, function(){zdrojClanok();});
	http_request.send(null);
}
function najdiZdroj(){   
	if (http_request.readyState == 4) {
    if (http_request.status == 200) {
    	document.getElementById('zdroj').innerHTML = http_request.responseText;
    	document.getElementById('zdroj').style.display = 'block';
    }
  }
}
function doNajdiZdroj(text, clanok_id){
	createRequest('GET', getPath()+'./admin_ajax.php', '?text='+text+'&typ=clanok_zdroj_search'+'&clanok_id='+clanok_id, function(){najdiZdroj();});
	http_request.send(null);
}

function clanokAvatarDelete(avatar_typ){   
	if (http_request.readyState == 4) {
    if (http_request.status == 200) {
    	document.getElementById('avatar'+avatar_typ).innerHTML = http_request.responseText;
    }
  }
}
function doClanokAvatarDelete(avatar_typ, clanok_id){
	createRequest('GET', getPath()+'./admin_ajax.php', '?typ=clanok_avatar_delete&avatar_typ='+avatar_typ+'&clanok_id='+clanok_id, function(){clanokAvatarDelete(avatar_typ);});
	http_request.send(null);
}

// otevira odkazy s ref=external v novem okne - kvuli nevalidite target=_blank v
// XHTML
function externalLinks() 
{
  if( !document.getElementsByTagName ) return;
  var anchors = document.getElementsByTagName('a');
  for( var i=0; i<anchors.length; i++ ) {
    var anchor = anchors[i];
    if( anchor.getAttribute('href') && anchor.getAttribute('rel') == 'external' )
       	anchor.target = '_blank';
    }
}

function addEvent2(elm, evType, fn, useCapture)
{
 if (elm.addEventListener){
   elm.addEventListener(evType, fn, useCapture);
   return true;
 } else if (elm.attachEvent){
   var r = elm.attachEvent("on"+evType, fn);
   return r;
 }
}

addEvent2(window,'load',externalLinks);


