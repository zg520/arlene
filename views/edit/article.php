<div class="column fullSized">
	<div id="statusSubmit">
		<span>Quick Actions <span class="ui-icon ui-icon-circle-triangle-s"></span></span>
		<form>
			<input type="radio" name="awaiting_changes" checked="checked" class="ui-helper-hidden-accessible" />
			<label for="awaiting_changes" class="ui-button ui-widget ui-state-default ui-button-text-only ui-corner-all"><span class="ui-button-text">Awaiting changes</span></label>
			<input type="radio" name="submitted" checked="checked" class="ui-helper-hidden-accessible" />
			<label for="submitted" class="ui-button ui-widget ui-state-default ui-button-text-only ui-corner-all"><span class="ui-button-text">Submitted</span></label>
			<input type="radio" name="under_review" checked="checked" class="ui-helper-hidden-accessible" />
			<label for="under_review" class="ui-button ui-widget ui-state-default ui-button-text-only ui-corner-all"><span class="ui-button-text">Under Review</span></label>
			<input type="radio" name="published" checked="checked" class="ui-helper-hidden-accessible" />
			<label for="published" class="ui-button ui-widget ui-state-default ui-button-text-only ui-corner-all"><span class="ui-button-text">Published</span></label>
		</form>
	</div>
	<article>
  <header>
    <h1><?php echo $viewBag['article'] -> title; ?></h1>
    <img src="<?php echo $viewBag['article'] -> coverUrl?>" alt="Cover Image">
  </header>
  <p><?php echo $viewBag['article'] -> body; ?></p>
  <footer style="text-align: right">
  	<p>Published: <time pubdate datetime="<?php echo $viewBag['article'] -> publishDate ;?>"><?php echo $viewBag['article'] -> publishDate ;?></time></p>
  	<p>Likes: <?php echo $viewBag['article'] -> likes ;?> vs <?php echo $viewBag['article'] -> dislikes ;?> Dislikes</p>
  	<small>Writers: <?php foreach($viewBag['article'] -> writers as $writer) echo $writer->userId.'/' ; ?></small></footer>
  	<?php if(isset($viewBag['article'] -> publicComments)):?>
  <section>
    <h2>Editor Comments</h2>
       <?php if(CurrentUser::hasEditorAccess()):?>
    	<form class="comment" method="POST" action="/edit/comment">
    	<fieldset>
    		<input class="hidden" type="text" name="article_id" value="<?php echo $viewBag['article'] -> id ?>" readonly>
    		<textarea name="comment">Leave your comment here.</textarea>
    		<input type="submit" value="Comment" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover">
    	</fieldset>
    </form>
    <?php endif; ?>
    <?php foreach($viewBag['article'] -> editorComments as $comment): ?>
    <article>
      <header>
      <h3>Posted by: <?php echo $comment -> userId ?></h3>
      <p><time pubdate datetime="<?php echo $comment -> datePublished; ?>"><?php echo $comment -> datePublished; ?></time></p>
    </header>
    	<p><?php echo $comment -> comment; ?></p>
    </article>
    <?php endforeach;?>
  </section>
  <?php endif;?>
</article>
</div>