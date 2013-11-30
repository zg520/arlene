<script src="/scripts/editContentForm.js"></script>
<?php
require (ROOT . DS . 'views' . DS . 'SharedAddNewContentForm.php');
?>
<div class="column fullSized">
	<div id="statusSubmit">
		<span>Status: <span id="articleStatus" title="<?php echo $viewBag['article'] -> status ?>"><?php echo str_replace("_", " ", $viewBag['article'] -> status) ?> </span></span>
		<button id="editArticle" class="" style="border-bottom: solid #FF3333">Edit <span class="ui-icon ui-icon-pencil"></span></button>
	</div>
	<article>
		<header>
			<h1 id="articleTitle"><?php echo $viewBag['article'] -> title; ?></h1>
			<h2 class="hidden" id="articleId"><?php echo $viewBag['article'] -> id; ?></h2>
			<img id="articleImage" src="<?php echo $viewBag['article'] -> coverUrl?>" alt="Cover Image">
	</header>
 		<p id="articleText"><?php echo $viewBag['article'] -> body; ?></p>
  <footer style="text-align: right">
  	<p>Created: <time pubdate datetime="<?php echo $viewBag['article'] -> publishDate; ?>"><?php echo $viewBag['article'] -> publishDate; ?></time></p>
  	<p>Likes: <?php echo $viewBag['article'] -> likes; ?> vs <?php echo $viewBag['article'] -> dislikes; ?> Dislikes</p>
  	<small>Writers: <?php
	foreach ($viewBag['article'] -> writers as $writer)
		echo $writer -> userId . '/';
 ?></small></footer>
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
    <?php endforeach; ?>
  </section>
  <?php endif; ?>
</article>
</div>
