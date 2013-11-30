<div class="column fullSized">
	<article>
  <header>
    <h1><?php echo $this->viewBag['article'] -> title; ?></h1>
    <img src="<?php echo $this->viewBag['article'] -> coverUrl?>" alt="Cover Image">
  </header>
  <p><?php echo $this->viewBag['article'] -> body; ?></p>
  <footer style="text-align: right">
  	<p>Published: <time pubdate datetime="<?php echo $this->viewBag['article'] -> publishDate; ?>"><?php echo $this->viewBag['article'] -> publishDate; ?></time></p>
  	<p><a href="/read/like/<?php echo $this->viewBag['article'] -> id; ?>">Likes</a> <?php echo $this->viewBag['article'] -> likes; ?> vs <?php echo $this->viewBag['article'] -> dislikes; ?> 
  		<a href="/read/dislike/<?php echo $this->viewBag['article'] -> id; ?>">Dislike</a></p>
  	<small>Writers: 
  		<?php
		foreach ($this->viewBag['article'] -> writers as $writer)
			echo $writer -> userId . '/';?>
			</small>
	</footer>
  <section class="comment">
    <h2>Comments</h2>
    <?php if(CurrentUser::hasSubscriberAccess()):?>
    <form method="POST" action="/read/comment">
    	<fieldset>
    		<input class="hidden" type="text" name="article_id" value="<?php echo $this->viewBag['article'] -> id ?>" readonly>
    		<textarea name="comment">Leave your comment here.</textarea>
    		<input type="submit" value="Comment" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover">
    	</fieldset>
    </form>
    <?php endif; ?>
    <?php if(isset($this->viewBag['article'] -> publicComments)):?>
    <?php foreach($this->viewBag['article'] -> publicComments as $comment): ?>
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