<div class="column fullSized">
	<article>
  <header>
    <h1><?php echo $this->viewBag['content'] -> title; ?></h1>
    <?php if(get_class($this->viewBag['content']) == "Review"): ?>
    	<h2><?php echo $this->viewBag['content'] -> topic; ?></h2>
   		<div>
   			<h3 id="rating"><span class="stars"><?php echo $this->viewBag['content'] -> rating; ?></span> </h3>
   		</div>
    <?php elseif (get_class($this->viewBag['content']) == "Column") :?>
    	<h2><?php echo $this->viewBag['content'] -> topic; ?></h2>
    <?php endif;?>
    <img src="<?php echo $this->viewBag['content'] -> coverUrl?>" id="articleImage" alt="Cover Image">
  </header>
  <p><?php echo $this->viewBag['content'] -> body; ?></p>
  <footer style="text-align: right">
  	<p>Published: <time pubdate datetime="<?php echo $this->viewBag['content'] -> publishDate; ?>"><?php echo $this->viewBag['content'] -> publishDate; ?></time></p>
  	<p><a href="read/like/<?php echo $this->viewBag['content'] -> id; ?>">Like</a> <?php echo $this->viewBag['content'] -> likes; ?> vs <?php echo $this->viewBag['content'] -> dislikes; ?> 
  		<a href="read/dislike/<?php echo $this->viewBag['content'] -> id; ?>">Dislike</a></p>
  	<small>Writers: 
  		<?php
		foreach ($this->viewBag['content'] -> writers as $writer)
			echo $writer -> userId . '/';?>
			</small>
	</footer>
  <section class="comment">
    <h2>Comments</h2>
    <?php if(CurrentUser::hasSubscriberAccess()):?>
    <form method="POST" action="read/comment">
    	<fieldset>
    		<input class="hidden" type="text" name="article_id" value="<?php echo $this->viewBag['content'] -> id ?>" readonly>
    		<textarea name="comment">Leave your comment here.</textarea>
    		<input type="submit" value="Comment" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover">
    	</fieldset>
    </form>
    <?php endif; ?>
    <?php if(isset($this->viewBag['content'] -> publicComments)):?>
    <?php foreach($this->viewBag['content'] -> publicComments as $comment): ?>
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