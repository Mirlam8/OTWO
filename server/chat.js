var chatManager = new function(){

	var idle 		= true;
	var interval	= 500;
	var xmlHttp		= new XMLHttpRequest();
	var finalDate	= '';

	// Ajax Setting
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
		{
			// JSON 포맷으로 Parsing
			var res = JSON.parse(xmlHttp.responseText);
			finalDate = res.time;

			// 채팅내용 보여주기
			chatManager.show(res.data);

			// 채팅내용 가져오기
			chatManager.proc();
		}
	}

	// 채팅내용 가져오기
	this.proc = function()
	{
		// Ajax 통신
		xmlHttp.open("GET", "proc.php?time="+encodeURIComponent(finalDate), true);
	  xmlHttp.send();
    
    var audio = new Audio('./res/orderbell.wav');
    audio.play();
  }

	// 채팅내용 보여주기
	this.show = function(data)
	{
    var o = document.getElementById('order');
		var ti, ta, or, div;

		// 채팅내용 추가
		for(var i=0; i<data.length; i++)
		{
      div = document.createElement('div');
      div.setAttribute("id", "Div"+i);
      div.setAttribute("class", "new");
      div.setAttribute("ondblclick", "finish('#Div" + i + "')");
      ti = document.createElement('p');
			ti.appendChild(document.createTextNode(data[i].time));
			div.appendChild(ti);
      
      ta = document.createElement('p');
			ta.appendChild(document.createTextNode(data[i].table_num+"번 테이블"));
			div.appendChild(ta);
      
      or = document.createElement('p');
			or.appendChild(document.createTextNode(data[i].order_menu));
			div.appendChild(or);
      
      o.appendChild(div);
		}
    o.scrollTop = o.scrollHeight;

	}
}

window.onload = function()
{
	chatManager.proc();
}
