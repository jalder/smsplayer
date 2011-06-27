$(document).ready(function() {
  $('.ajax').click(function() {
	  var href = $(this).attr("name");
	  href = "/mpd/"+href;
	  $.post(href);
	  
	  return false;
  });
  $('#refreshlist').click(function() {
	  var href = $(this).attr("name");
	  href = "/mpd/"+href;
	  $.post(href, function(data){
		  $('#olplaylist').html(data);
		  //alert(data);
	  },'html');
	  
	  return false;
  }); 
  
  $('.dialog').dialog({'autoOpen': false, 'width': '700px'});
  $('.dialog_open').click(function() {
	  var id = $(this).attr("name");
	  id = "#result"+id;
	  $(id).dialog('open');
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
  
  $('form.ytdownload').submit(function(){
	  var data = $(this).serialize();
	  
	  $.post('/youtube/download',data);
	  //refresh download list here
//	  var href = "/mpd/getplaylist"
//	  $.post(href, function(data){
//		  $('#olplaylist').html(data);
//		  //alert(data);
//	  },'html');
	  
	  alert('Added to download queue');
	  $('.dialog').dialog('close');
	  
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

  
  $('form.encode').submit(function(){
	  var data = $(this).serialize();
	  
	  $.post('/youtube/encode',data);
	  alert('Song is encoding');
	  return false;
	  
  });
});