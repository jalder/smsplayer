<?php foreach($dlqueue as $file): ?>
<li><?php echo $file['file'] ;?> - <?php echo $file['status']; ?><form method="post" action="/youtube/encode" class="encode"><input type="hidden" name="file" value="<?php echo $file['file']; ?>" /><input type="submit" value="Encode" /></form></li>
<?php endforeach; ?>