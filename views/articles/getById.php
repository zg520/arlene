<div class="column fullSized">
	<article>
  <header>
    <h1><?php echo $viewBag['article'] -> title; ?></h1>
    <img src="<?php echo $viewBag['article'] -> coverUrl?>" alt="Cover Image">
  </header>
  <p><?php echo $viewBag['article'] -> body; ?></p>
  <footer style="text-align: right">
  	<p>Published: <time pubdate datetime="<?php echo $viewBag['article'] -> publishDate ;?>"><?php echo $viewBag['article'] -> publishDate ;?></time></p>
  	<small>Writers: <?php echo $viewBag['article'] -> writers; ?></small></footer>
  <section>
    <h2>Comments</h2>
    <article>
      <header>
      <h3>Posted by: Apple Lover</h3>
      <p><time pubdate datetime="2009-10-10T19:10-08:00">~1 hour ago</time></p>
    </header>
    <p>I love apples, my favourite kind are Granny Smiths</p>
    </article>
    
    <article>
      <header>
        <h3>Posted by: Oranges are king</h3>
        <p><time pubdate datetime="2009-10-10T19:15-08:00">~1 hour ago</time></p>
      </header>
      <p>Urgh, apples!? you should write about ORANGES instead!!1!</p>
    </article>
  </section>
</article>
</div>