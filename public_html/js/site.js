$(document).ready(function() {
  $('.ajax').click(function() {
	  var href = $(this).attr("name");
	  href = "/mpd/"+href;
	  $.post(href);
	  refreshlist();
	  return false;
  });
  refreshlist();
  $('#refreshlist').click(function() {

		refreshlist();
	  
	  return false;
  }); 
  
  $('.dialog').dialog({'autoOpen': false, 'width': '700px'});
  $('.dialog_open').click(function() {
	  var id = $(this).attr("name");
	  id = "#result"+id;
	  $(id).dialog('open');
	  return false;
  });
  
  
  $('#refresh_dlqueue').click(function(){
	  refreshdlqueue();
	  
	  return false;
	  
  });
  
  $('#refresh_requests').click(function() {
	  
	  $.post('/sms/fetchnew',function(data){
		  $('#request_queue').html(data);
		  $('.dialog').dialog({'autoOpen': false, 'width': '700px'});
		  $('.dialog_open').click(function() {
			  var id = $(this).attr("name");
			  id = "#result"+id;
			  $(id).dialog('open');
			  return false;
		  });
	  },'html');
	  return false;
  });
  
//  $('.download').click(function() {
//	  var id = $(this).attr('name');
//	  var form = '#youtube_download'+id;
//	  alert(form);
//	  $.post('/youtube/download',$(form).serialize(),function(data){
//		  alert(data);
//		  
//	  },'html');
//	  
//	  return false;
//  });
  
  $('.ytdownload').submit(function(){
	  var data = $(this).serialize();
	  
	  $.post('/youtube/download',data);

	  
	  alert('Added to download queue');
	  
	  return false;
	  
  });
  
  $('a.ajax').button();
  
  $('form.addsong').submit(function(){
	  var data = $(this).serialize();
	  
	  $.post('/mpd/addsong',data);
	  var href = "/mpd/getplaylist"
	  $.post(href, function(data){
		  $('#olplaylist').html(data);
	  },'html');
	  return false;
	  
  });

  $('.deleteSong').click(function(){
	  var id = $(this).attr('id');
	  $.get('/mpd/deleteSongId?id='+id);
	  $('li#'+id).remove();
	  return false;
	  
  });
  
  
  $('form.encode').submit(function(){
	  var data = $(this).serialize();
	  
	  $.post('/youtube/encode',data);
	  alert('Song is encoding');
	  return false;
	  
  });
  function refreshlist(){
	  var href = 'getplaylist';
	  href = "/mpd/"+href;
	  $.post(href, function(data){
		  $('#olplaylist').html(data);
		  $.post('/mpd/getcurrentsong', function(data){
			  //alert(data);
			  $('#olplaylist #'+data).addClass('current');
			  $('#olplaylist').sortable({ 
				  start: function(event, ui) {
		            	start = ui.item.prevAll().length;
			  		},
				    update : function (event, ui) { 
				      movehref = '/mpd/movesong?from='+start+'&to='+(ui.item.prevAll().length);
				      $.get(movehref);
				      
				    } 
				  });
			  $('.deleteSong').click(function(){
				  var id = $(this).attr('id');
				  $.get('/mpd/deleteSongId?id='+id);
				  $('li#'+id).remove();
				  return false;
				  
			  });
		  },'html');
	  },'html');
	  

	
}
  
  function refreshdlqueue(){
	 var href =  'index.php/index/getdlqueue';  
	 $.post(href,function(data){
		 //alert(data);
		 $('#oldlqueue').html(data);
	 },'html');
  }

});

