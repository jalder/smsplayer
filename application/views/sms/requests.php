	<ol>
	<?php foreach($requests as $k=>$r) : ?>
		<li><label>From</label><?php echo $r['number']; ?><label>Request</label><a href="#" class="dialog_open" name="<?php echo $k; ?>"><?php echo $r['message']; ?></a></li>
	<?php endforeach; ?>
	</ol>
	<?php foreach($requests as $k=>$r) : ?>
		<div class="dialog" id="result<?php echo $k; ?>" title="<?php echo $r['message']; ?>">
			<?php foreach($r['youtube'] as $i=>$v) : ?>
				<div class="">
				<form id="youtube_download<?php echo $i; ?>" action="/youtube/download" method="post" class="ytdownload">
		 			<input type="hidden" name="url" value="<?php echo $v['url']; ?>" />
		 			<input type="hidden" name="file" value="<?php $file = explode(' ',$v['title']); echo $file[0].rand(); ?>.flv" />
		 			<input type="submit" value="Download" />
					<?php echo $v['thumb'].' '.$v['title'].''; ?>
				</form>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>