<script src="/scripts/editContentForm.js"></script>
<?php
require (ROOT . DS . 'views' . DS . 'SharedAddNewContentForm.php');
?>
<div class="column fullSized">
	<div id="statusSubmit">
		<span>Status: <span id="articleStatus" data-status="<?php echo $this -> viewBag['content'] -> status ?>"><?php echo str_replace("_", " ", $this -> viewBag['content'] -> status) ?> </span></span>
		<button id="editArticle" class="" style="border-bottom: solid #FF3333">Edit <span class="ui-icon ui-icon-pencil"></span></button>
	</div>
	<article id="mainContent" data-id="<?php echo $this->viewBag['content'] -> id ?>" data-type="<?php echo get_class($this->viewBag['content']) ?>">
		<header>
		    <h1 id="articleTitle"><?php echo $this->viewBag['content'] -> title; ?></h1>
		    <?php if(get_class($this->viewBag['content']) == "Review"): ?>
		    	<h2 id="articleTopic"><?php echo $this->viewBag['content'] -> topic; ?></h2>
		   		<div><h3 id="articleRating"><?php echo $this->viewBag['content'] -> rating; ?></h3></div>
		    <?php elseif (get_class($this->viewBag['content']) == "Column") :?>
		    	<h2 id="articleTopic"><?php echo $this->viewBag['content'] -> topic; ?></h2>
		    <?php endif;?>
			<img id="articleImage" src="<?php echo $this -> viewBag['content'] -> coverUrl?>" alt="Cover Image">
	</header>
 		<p id="articleText"><?php echo $this -> viewBag['content'] -> body; ?></p>
  <footer style="text-align: right">
  	<p>Created: <time pubdate datetime="<?php echo $this -> viewBag['content'] -> createdDate; ?>"><?php echo $this -> viewBag['content'] -> createdDate; ?></time></p>
  	<p>Likes: <?php echo $this -> viewBag['content'] -> likes; ?> vs <?php echo $this -> viewBag['content'] -> dislikes; ?> Dislikes</p>
  	<small>Writers: <?php
	foreach ($this -> viewBag['content'] -> writers as $writer)
		echo $writer -> userId . '/';
 ?></small></footer>
  	<?php if(isset($this -> viewBag['content'] -> publicComments)):?>
  <section>
    <h2>Editor Comments</h2>
       <?php if(CurrentUser::hasEditorAccess()):?>
    	<form class="comment" method="POST" action="/edit/comment">
    	<fieldset>
    		<input class="hidden" type="text" name="article_id" value="<?php echo $this -> viewBag['content'] -> id ?>" readonly>
    		<textarea name="comment">Leave your comment here.</textarea>
    		<input type="submit" value="Comment" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only ui-state-hover">
    	</fieldset>
    </form>
    <?php endif; ?>
    <?php foreach($this -> viewBag['content'] -> editorComments as $comment): ?>
    <article>
      <header>
      <h3>Posted by: <?php echo $comment -> userId ?></h3>
      <p><time pubdate datetime="<?php echo $comment -> datePublished; ?>"><?php echo $comment -> datePublished; ?></time></p>
    </header>
    	<p><?php echo $comment -> comment; ?></p>
    </article>
    <?php endforeach; ?>
  </section>
  <?php endif; ?>
</article>
</div>
