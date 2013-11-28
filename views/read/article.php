<div class="column fullSized">
	<article>
  <header>
    <h1><?php echo $viewBag['article'] -> title; ?></h1>
    <img src="<?php echo $viewBag['article'] -> coverUrl?>" alt="Cover Image">
  </header>
  <p><?php echo $viewBag['article'] -> body; ?></p>
  <footer style="text-align: right">
  	<p>Published: <time pubdate datetime="<?php echo $viewBag['article'] -> publishDate; ?>"><?php echo $viewBag['article'] -> publishDate; ?></time></p>
  	<p><a href="/read/like/<?php echo $viewBag['article'] -> id; ?>">Likes</a> <?php echo $viewBag['article'] -> likes; ?> vs <?php echo $viewBag['article'] -> dislikes; ?> 
  		<a href="/read/dislike/<?php echo $viewBag['article'] -> id; ?>">Dislike</a></p>
  	<small>Writers: 
  		<?php
		foreach ($viewBag['article'] -> writers as $writer)
			echo $writer -> userId . '/';?>
			</small>
	</footer>
  <section class="comment">
    <h2>Comments</h2>
    <?php if(CurrentUser::hasSubscriberAccess()):?>
    <form method="POST" action="/read/comment">
    	<fieldset>
    		<input class="hidden" type="text" name="article_id" value="<?php echo $viewBag['article'] -> id ?>" readonly>
    		<textarea name="comment">Leave your comment here.</textarea>
    		<input type="submit" value="Comment" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover">
    	</fieldset>
    </form>
    <?php endif; ?>
    <?php if(isset($viewBag['article'] -> publicComments)):?>
    <?php foreach($viewBag['article'] -> publicComments as $comment): ?>
    <article>
      <header>
      <h3>Posted by: <?php echo $comment -> userId ?></h3>
      <p><time pubdate datetime="<?php echo $comment -> datePublished; ?>"><?php echo $comment -> datePublished; ?></time></p>
    </header>
    	<p><?php echo $comment -> comment; ?></p>
    </article>
    <?php endforeach; ?>
    <?php endif; ?>
  </section>
</article>
</div>