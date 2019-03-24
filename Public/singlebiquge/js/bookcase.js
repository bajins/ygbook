function LastRead(){this.bookList="bookList"}
LastRead.prototype={
	set:function(bid,tid,title,texttitle,author,sortname,subid){
		if(!(bid&&tid&&title&&texttitle&&author&&sortname&&subid))return;
		var v=bid+'#'+tid+'#'+title+'#'+texttitle+'#'+author+'#'+sortname+'#'+subid;
		this.setItem(bid,v);
		this.setBook(bid)
	},
	get:function(k){
		return this.getItem(k)?this.getItem(k).split("#"):"";
	},
	remove:function(k){
		this.removeItem(k);
		this.removeBook(k)
	},
	setBook:function(v){
		var reg=new RegExp("(^|#)"+v);
		var books =	this.getItem(this.bookList);
		if(books==""){
			books=v
			}
		 else{
			 if(books.search(reg)==-1){
				 books+="#"+v
				 }
			 else{
				  books.replace(reg,"#"+v)
				 }
			 }
		this.setItem(this.bookList,books)
	},
	getBook:function(){
		var v=this.getItem(this.bookList)?this.getItem(this.bookList).split("#"):Array();
		var books=Array();
		if(v.length){
			for(var i=0;i<v.length;i++){
				var tem=this.getItem(v[i]).split('#');
				if (tem.length>3)books.push(tem);
				}
			}
		return books
	},
	removeBook:function(v){
	    var reg=new RegExp("(^|#)"+v);
		var books=this.getItem(this.bookList);
		if(!books){
			books=""
			}
		 else{
			 if(books.search(reg)!=-1){
			      books=books.replace(reg,"")
				 }
			 }
		this.setItem(this.bookList,books)
	},
	setItem:function(k,v){
		if(!!window.localStorage){
			localStorage.setItem(k,v);
		}
		else{
			var expireDate=new Date();
			  var EXPIR_MONTH=30*24*3600*1000;
			  expireDate.setTime(expireDate.getTime()+12*EXPIR_MONTH)
			  document.cookie=k+"="+encodeURIComponent(v)+";expires="+expireDate.toGMTString()+"; path=/";
		}
	},
	getItem:function(k){
		var value=""
		var result=""
		if(!!window.localStorage){
			result=window.localStorage.getItem(k);
			 value=result||"";
		}
		else{
			var reg=new RegExp("(^| )"+k+"=([^;]*)(;|\x24)");
			var result=reg.exec(document.cookie);
			if(result){
				value=decodeURIComponent(result[2])||""}
		}
		return value
	},
	removeItem:function(k){
		if(!!window.localStorage){
		 window.localStorage.removeItem(k);
		}
		else{
			var expireDate=new Date();
			expireDate.setTime(expireDate.getTime()-1000)
			document.cookie=k+"= "+";expires="+expireDate.toGMTString()
		}
	},
	removeAll:function(){
		if(!!window.localStorage){
		 window.localStorage.clear();
		}
		else{
		var v=this.getItem(this.bookList)?this.getItem(this.bookList).split("#"):Array();
		var books=Array();
		if(v.length){
			for( i in v ){
				var tem=this.removeItem(v[k])
				}
			}
			this.removeItem(this.bookList)
		}
	}
}
function zzleft(mainStr,lngLen) { 
	if (lngLen>0) {return mainStr.substring(0,lngLen)} 
	else{return null} 
}
function loadbooker(){
	var bookhtml='';
	var books=lastread.getBook();
	var books=books.reverse();
	if(books.length){
		for(var i=0 ;i<books.length;i++){
			if(i<=100){
				bookid = books[i][0];
				readurl = view_rule.replace('{}', '');
				readurl = readurl.replace('{subid}', Math.floor(bookid/1000));
				readurl = readurl.replace('{id}', parseInt(bookid));
				readurl = readurl.replace('{dir}', books[i][6]);
				chapterurl = chapter_rule.replace('{}', '');
				chapterurl = chapterurl.replace('{subid}', Math.floor(bookid/1000));
				chapterurl = chapterurl.replace('{id}', parseInt(bookid));
				chapterurl = chapterurl.replace('{dir}', books[i][6]);
				chapterurl = chapterurl.replace('{cid}', parseInt(books[i][1]));
				bookhtml+='<li><span class="s1">'+books[i][5]+'</span><span class="s2"><a href="'+readurl+'" target="_blank">'+books[i][2]+'</a></span><span class="s3"><a href="'+chapterurl+'" target="_blank">'+books[i][3]+'</a></span><span class="s4">'+books[i][4]+'</span><span class="s5"><a href="javascript:removebook(\''+books[i][0]+'\')">删除</a></span><span class="s6">&nbsp;</span><span class="s7">&nbsp;</span></li>';
			}
		}
	}else{
	 	bookhtml+='<div style="height:100px;line-height:100px; text-align:center">还木有任何书籍( ˙﹏˙ )</div>';
	}
	$(".read_book").html(bookhtml);
}
function fordele(){
	var bookhtml='';
	var books=lastread.getBook();
	var books=books.reverse();
	if(books.length){
		for(var i=0 ;i<books.length;i++){
			if(i<=100){
				bookhtml+='<li><span class="s1">'+books[i][5]+'</span><span class="s2"><a href="/'+books[i][6]+'/'+books[i][0]+'" target="_blank">'+books[i][2]+'</a></span><span class="s3"><a href="/'+books[i][6]+'/'+books[i][0]+'/'+books[i][1]+'.html" target="_blank">'+books[i][3]+'</a></span><span class="s4">'+books[i][4]+'</span><span class="s5"><a href="javascript:removebook(\''+books[i][0]+'\')">删除</a></span><span class="s6">&nbsp;</span><span class="s7">&nbsp;</span></li>';
			}
		}
	}
	else{
		bookhtml+='<div style="height:100px;line-height:100px; text-align:center">还木有任何书籍( ˙﹏˙ )</div>';
	}
	$(".read_book").html(bookhtml);
}
function removebook(k){lastread.remove(k);fordele();}
window.lastread = new LastRead();