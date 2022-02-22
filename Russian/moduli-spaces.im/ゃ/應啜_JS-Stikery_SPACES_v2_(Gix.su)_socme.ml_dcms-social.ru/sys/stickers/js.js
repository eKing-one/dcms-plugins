function stickersEnabled(){
	return !!!document.getElementById("stickers_off");
}

var Stickers_bought_info = [{"id":"1","info_link":"","bought":"0"},{"id":"2","info_link":"","bought":"0"},{"id":"3","info_link":"","bought":"0"},{"id":"4","info_link":"","bought":"1"}]
var Stickers_array = [["megusta11|megusta","please11|please","determined11|determined","youdontsay11|youdontsay","yaoming11|yaoming","trollface11|trollface","happyface11|happyface","thumbsup11|thumbsup","cageyoudontsay11|cageyoudontsay","areyouserious11|areyouserious","facesfemale11|facesfemale","no11|no","kiddingme11|kiddingme","facepalm11|facepalm","forevelalone11|forevelalone","pftch11|pftch","lol11|lol","love11|love","duckyeah11|duckyeah","ifyouknow11|ifyouknow","pokerface11|pokerface","notbad11|notbad","accepted11|accepted","trollfacewoman11|trollfacewoman","okay11|okay","whatyoutalk11|whatyoutalk","rocknroll11|rocknroll","goodjob11|goodjob","alone11|alone","truestory11|truestory","angry11|angry11","bad11|bad11","cspitting11|cerealSpitting11","cry11|cry11","deskflip11|deskflip11","drunk11|drunk11","evil11|evil11","ffuu11|ffuu11","ohgod11|ohGod11","rpuke11|rainbowPuke11","smile11|smile11","spman11|spiderpman11","stare11|stare11","tfeel11|thatFeel11","thinks11|thinks11","thumbup11|thumbup11","veryangry11|veryangry11","what11|what11","yuno11|yuno11","atthings11|allthethings11","gaspcat11|gaspcat11","hahahah11|hahahah11","tffemale11|theduckFemale11","tffBlonde11|theduckFemaleBlonde11","why11|why11","amazed11|amazed","awesome11|awesome","axe11|axe","badpoker11|badpoker","bored11|bored","cereal11|cereal","compdude11|compdude","concent11|concentratedsw","conflic11|conflicting","ctroll11|crazytroll","danctroll11|dancingtroll","devtroll11|deviltroll","fap11|fap","freddie11|freddie","ducku11|ducku","gangnam11|gangnam","grin11|grin","harp11|harp","imwatch11|imwatchingu","lied11|lied","milk11|milk","news11|newsguy","newstear11|newsguytear","yoba|noone","notodohere11|nothingtodohere","notokay11|notokay","godwhy11|ohgodwhy","ohstopit11|ohstopit","ololo11|ololo","omsk11|omsk","pedobear11|pedobear","slowpke11|slowpoke","reaction11|sreaction","ysure11|yeahsure"],["spacer22|kos_spaces","hi22|kos_hi","yes22|kos_yes","laugh22|kos_laugh","cool22|kos_awesome","inlove22|kos_love","angel22|kos_angel","play22|kos_play","thanks22|kos_thanks","flowers22|kos_flowers","shy22|kos_confused","rock22|kos_rock","bye22|kos_bye","no22|kos_no","lol22|kos_lol","sorry22|kos_sorry","facepalm22|kos_facepalm","tired22|kos_tired","work22|kos_busy","wonder22|kos_surprise","horror22|kos_affright","devil22|kos_anger"],["bbsmile33|blonde_big_smile","bconcentrated33|blonde_concentrated","bcrying33|blonde_crying","bdazed33|blonde_dazed","bdetermined33|blonde_determined","bdude33|blonde_dude","bewbte33|blonde_ewbte","betears33|blonde_excited_tears","bfalone33|blonde_forever_alone","bfchtb33|blonde_duck_that_bitch","bfyeah33|blonde_duck_yeah","bhmmm33|blonde_hmmm","blol33|blonde_lol","bmgusta33|blonde_me_gusta","bokay33|blonde_okay","bpfft33|blonde_pfft","bpface33|blonde_pokerface","brgpssd33|blonde_rage_getting_pissed","brmad33|blonde_rage_mad","brquiet33|blonde_rage_quiet","brtongue33|blonde_red_tongue","bsmile33|blonde_smile","btsad33|blonde_troll_sad","btroll33|blonde_troll","bwnhands33|blonde_why_no_hands","bquite33|blonde_quite","brmad233|blonde_rage_mad2","brshaking33|blonde_rage_shaking","brsuper33|blonde_rage_super","bstears33|blonde_sweet_tears"],["cybeee44|cosm_yur_beee","cyda44|cosm_yur_da","cyfacepalm44|cosm_yur_facepalm","cyheiyou44|cosm_yur_hei-you","cyhello44|cosm_yur_hello","cybye44|cosm_yur_bye","cyhmmm44|cosm_yur_hmmm","cyhungry44|cosm_yur_hungry","cyklass44|cosm_yur_klass","cylovely44|cosm_yur_lovely","cymolchy44|cosm_yur_molchy","cynet44|cosm_yur_net","cyneznayu44|cosm_yur_ne-znayu","cynonono44|cosm_yur_nonono","cyobidelsya44|cosm_yur_obidelsya","cyobnimi44|cosm_yur_obnimi","cyok44|cosm_yur_ok","cyomnomnom44|cosm_yur_omnomnom","cyplachu44|cosm_yur_plachu","cysexy44|cosm_yur_sexy","cyskuchayu44|cosm_yur_skuchayu","cyskuchno44|cosm_yur_skuchno","cysleepy44|cosm_yur_sleepy","cystop44|cosm_yur_stop","cysuperman44|cosm_yur_super-man","cysvoboden44|cosm_yur_svoboden","cyuhaha44|cosm_yur_uhaha","cyyessir44|cosm_yur_yes-sir","cyzanyat44|cosm_yur_zanyat"]];
   
   
var categories_toggler = '';
if (stickersEnabled()){
	categories_toggler += '<div id="categories_toggler_menu" class="t_center">  <a href="#ct_2" id="ct2" onclick="return selectCategory(\'1\',\'st/t/\')"><img src="/sys/stickers/img/megusta_min.png" class="m" alt="" title="Мемы" height="20" width="20" /></a> <a href="#ct_3" id="ct3" onclick="return selectCategory(\'2\',\'st/t/\')"><img src="/sys/stickers/img/kosm_ico.gif" class="m" alt="" title="Косьминожки" height="20" width="20" /></a> <a href="#ct_4" id="ct4" onclick="return selectCategory(\'3\',\'st/t/\')"><img src="/sys/stickers/img/blonde.gif" class="m" alt="" title="Ragefaces Female" height="20" width="20" /></a> <a href="#ct_5" id="ct5" onclick="return selectCategory(\'4\',\'st/t/\')"><img src="/sys/stickers/img/cytumb.gif" class="m" alt="" title="Космонавт Лёха" height="20" width="20" /></a> ';
	categories_toggler += '<a href="#" onclick="toggle(\'view-all-smiles\'); return false;  "><span class="close"><img src="/sys/stickers/img/cross_r.gif" alt="" /></span></a> </div>';
}




function selectCategory(category,directory){
	category = category - 1;
	if (directory){
		var this_group_smiles_count = Stickers_array[category].length;
	}else{
		var this_group_smiles_count = Smiles_array[category].length;
	}
	var smilesHtml = "", hide_on_click_element = "";
	
	smilesHtml ='<div id="quote-view-all-smiles" style="margin:0 auto;">';
	smilesHtml += categories_toggler;
	smilesHtml += '<div id="smiles_inner_wrap">';
	
	hide_on_click_element = 'quote-view-all-smiles';
	
	if (directory){
		var currentCategoryInfo = Stickers_bought_info[category];
		
		smilesHtml += "<div class='category_wrap t_center'>";

		if (currentCategoryInfo.bought == '0'){
			smilesHtml += "<div>";
		}
		
		
	}
	
	

	for (var i=0; i<this_group_smiles_count; i++){
		if (directory){
			var this_el_arr = Stickers_array[category][i].split("|");
		}else{
			var this_el_arr = Smiles_array[category][i].split("|");
		}
		var special_el = this_el_arr[0].indexOf("@");
		if (special_el < 0){
			var smile_text = ":" + this_el_arr[0];
		}else{
			var smile_text = this_el_arr[0].substring(1);
		}
		
		smile_text = smile_text.replace(/\'/,"\\'");
		
		if (!directory){
			directory = "";
		}
		
		if (this_el_arr.length > 1){
			var smile_gif = "/sys/stickers/images/" + this_el_arr[1] + ".gif";
		}
		smilesHtml += "<a href='#smile"+category+"-"+i+"' onclick=\"return selectSmile('"+smile_text+"','"+hide_on_click_element+"')\"><img alt='' src='"+smile_gif+"' /></a>";

	}
	
	
	
	
	
	
	document.getElementById("block_for_stickers").innerHTML=smilesHtml;
	var hide = document.getElementById('quote-show-smiles-categories');
	hide.style.display = 'none';
	
	
	

	return false; 
}







function selectSmile(text1,elementHide) {
	text1 = text1 + ' ';
	var text3 = '',
	    element = document.getElementById("textarea"),
	    caretPos = getCaretPos(element) + (text1.length);
	
	element.focus();
	if (document.selection) {
		var selected = document.selection.createRange(); 
		selected.text = text3 + text1  + text3; 
	} else if (element.selectionStart !== undefined) {
		var str = element.value; 
		var start = element.selectionStart; 
		var length = element.selectionEnd - element.selectionStart; 
		element.value = str.substr(0, start) + text3 + text1  + text3 + str.substr(start + length); 
	} else {
		element.value += text3 + text1 + text3; 
	}
	
	if (elementHide){
		document.getElementById(elementHide).style.display = "none";
	}

	setCaretPosition(element, caretPos);
	return false; 
}

function getCaretPos(obj) {
	if (document.selection) { // IE
		var sel = document.selection.createRange();
		var clone = sel.duplicate();
		sel.collapse(true);
		clone.moveToElementText(obj);
		clone.setEndPoint('EndToEnd', sel);
		return clone.text.length;
	} else if (obj.selectionStart!==false){
		return obj.selectionStart; // Gecko
	} else {
		return 0;
	}
}

function setCaretPosition(ctrl, pos) {
    if(ctrl.setSelectionRange) {
        ctrl.focus();
        ctrl.setSelectionRange(pos,pos);
    }
    else if (ctrl.createTextRange) {
        var range = ctrl.createTextRange();
        range.collapse(true);
        range.moveEnd('character', pos);
        range.moveStart('character', pos);
        range.select();
    }
}
						
function toggle(id,inline) {
	var quote = document.getElementById('quote-' + id);
	var state = quote.style.display;
	if (inline){
		if(state == 'none') {
			quote.style.display = 'inline';
		} else {
			quote.style.display = 'none';
		}	
	}else{
		if(state == 'none') {
			quote.style.display = 'block';
		} else {
			quote.style.display = 'none';
		}
	}
}

function addStickerButton(){
	if (!document.getElementById('quote-show-smiles-categories')){
		selectCategory('1','st/t/');
	} else {
		toggle('show-smiles-categories');
		document.getElementById('quote-view-all-smiles').style.display = 'none';
	}
	return false;
}



document.body.setAttribute('onclick','Smiles.bodyClicker(event)');
if (document.getElementById("addStickerButton2")){
	document.getElementById("addStickerButton2").style.display = "block";
}