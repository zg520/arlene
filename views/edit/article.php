<div class="column fullSized">
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
    <?php foreach($viewBag['article'] -> publicComments as $comment): ?>
    <article>
      <header>
      <h3>Posted by: <?php echo $comment -> userId ?></h3>
      <p><time pubdate datetime="<?php echo $comment -> publishDate; ?>"><?php echo $comment -> publishDate; ?></time></p>
    </header>
    	<p><?php echo $comment -> text; ?></p>
    </article>
    <?php endforeach;?>
  </section>
  <?php endif;?>
</article>
</div>