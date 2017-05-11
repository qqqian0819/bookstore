//检查电话格式是否正确的函数

  function checkregtel(regtel){
	var str=regtel;
	var expression=/^13(\d{9})$|^18(\d{9})$|^15(\d{9})$/;
	var objexp=new RegExp(expression);
	if(objexp.test(str)==true){
		return true;
	}
	else{
		return false;
	}
}
//检查时间是否逾期的函数
function checktime(time){
	var today = new Date();
	var losttime = new Date(time);
	var a=today-losttime;
	if(a>0){
		return true;
		
	}
	else
	{
		 return false;
	}
}
//检查字数是否超过限制字数的函数
function checkstr(str,digit){
			var n=0;
			for(i=0;i<str.length;i++){
				var leg=str.charCodeAt(i);
				if(leg>255){
					n+=2;
				}else{
					n+=1;
				}
			}
			if(n>digit){
				return true;
			}
			else{
				return false;
			}
		}
		//开始进行检查
		   function chkinput(form){
			   if(form.title.value==""){
		check_title.innerHTML="请输入标题且标题不超过20字！";
		check_title.style.color='red';
		return false;
	}else if(checkstr(form.title.value,40)){
			check_title.innerHTML="你输入的标题超20字！请重新输入";
			check_title.style.color='red';
			return false;
		
	}else{
	check_title.innerHTML="";	
	if(form.kind.value==""){
		check_kind.innerHTML="请选择你的失物/寻物类别！";
		check_kind.style.color='red';
		return false;
	}else{
		check_kind.innerHTML="";
	if(form.losttime.value==""){
		check_losttime.innerHTML="请选择你的丢失日期！";
		check_losttime.style.color='red';
		return false;
	
	}
	else
	{
		check_losttime.innerHTML="";
	if(form.place.value==""){
		check_place.innerHTML="请选择你的丢失地点！";
		check_place.style.color='red';
		return false;
	}
	else
	{
		check_place.innerHTML="";
	if(form.contact.value==""){
		check_contact.innerHTML="请输入你的姓名！";
		check_contact.style.color='red';
		return false;
	}
	else
	{
		check_contact.innerHTML="";
	 if(form.QQ.value==""){
	check_QQ.innerHTML="请输入QQ号码!";
	check_QQ.style.color='red';
			return false;
		}
		else if(isNaN(form.QQ.value)){
			
			check_QQ.innerHTML="QQ号码由数字组成!";
		check_QQ.style.color='red';
			return false;
		}else{
			check_QQ.innerHTML="";
	if(form.phone.value==""){
			check_phone.innerHTML="请输入电话号码";
				check_phone.style.color='red';
			
			return false;
		}else if(!checkregtel(form.phone.value)){
			check_phone.innerHTML="电话号码的格式不正确";
				check_phone.style.color='red';
		return false;
		} else
	{
		check_phone.innerHTML=""; 
		if(form.description.value==""){
			check_description.innerHTML="请输入备注且备注不超过300字";
				check_description.style.color='red';
			
			return false;
		}
		if(checkstr(form.description.value,600)){
			check_description.innerHTML="你输入的内容超过300字！";
				check_description.style.color='red';
		return false;
	}
	}
		}
	}
	}
	}
	}
	}
			
			
		   }