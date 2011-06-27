<html>
<head>
<title>SMS Player</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js" type="text/javascript"></script>
<script src="/js/site.js" type="text/javascript"></script>
<link href="/css/jquery-ui-1.8.13.custom.css" type="text/css" rel="stylesheet"></link>
<link href="/css/site.css" type="text/css" rel="stylesheet"></link>
</head>
<body>

<div style="text-align: center;"><h1>SMSPlayer</h1></div>

<div id="playlist">
	<h2>Playlist</h2>
	<div id="controls">
		<ul>
		<?php foreach($controls as $c): ?>
			<li><?php echo $c; ?></li>
		<?php endforeach; ?>
		</ul>
	</div>
	<a href="#" name="getplaylist" id="refreshlist" >refresh</a>
	<ol id="olplaylist">
	<?php if(isset($playlist['file'])){ foreach($playlist['file'] as $song) : ?>
	 	<li><?php if(isset($song['Artist'])){echo $song['Artist'];}?> - <?php if(isset($song['Title'])){echo $song['Title'];}?></li>	
	<?php endforeach; } ?>
	</ol>
</div>

<div id="requests">
<h2>Requests</h2>
<a href="#" id="refresh_requests">refresh</a>
	<div id="request_queue">
		<?php
		$requestview = View::factory('sms/requests');
		$requestview->requests = $requests;
		$requestview->render();
		?>
	</div>
</div>

<div id="dlqueue">
<h2>Downloading</h2>
<?php foreach($dlqueue as $file): ?>
<?php echo $file['file'] ;?> - <?php echo $file['status']; ?><form method="post" action="/youtube/encode" class="encode"><input type="hidden" name="file" value="<?php echo $file['file']; ?>" /><input type="submit" value="Encode" /></form><br />
<?php endforeach; ?>
</div>

<div id="encoding">
<h2>Encoding</h2>
<?php $i = 1; foreach($encoding as $file): ?>
<?php echo $file['file'] ;?> - <?php echo $file['status']; ?><form method="post" action="#" class=""><input type="hidden" name="file" value="<?php echo $file['file']; ?>" /><input type="submit" value="Queue" /></form><br />
<?php $i++; endforeach; ?>
</div>
<br style="clear: both;" />
<div id="library">
<h2>Database</h2>
<a href="/mpd/refreshdb">refresh</a>
<ol>
<?php if(isset($database['file'])){ foreach($database['file'] as $song): ?>
	<li><?php echo $song; ?><form method="post" action="/mpd/addsong" class="addsong"><input type="hidden" value="<?php echo $song; ?>" name="song" /><input type="submit" value="add" /></form></li>
<?php endforeach; } ?>
</ol>
</div>




</body>


</html>

