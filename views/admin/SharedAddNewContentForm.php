<div id="addNewForm-div">
			<div class="validateTips"></div>
			<form id="add-new-form" method="POST">
				<fieldset>
					<div id="title-field">
						<label for="title" style="display: block;" autofocus>title</label>
						<input type="text" name="title" class="text ui-widget-content ui-corner-all" style="display: block;" required/>
					</div>
					<div id="imgUrl-field">
 						<label for="imgUrl" style="display: block;">Image Url</label>
						<input type="text" name="imgUrl" class="text ui-widget-content ui-corner-all" style="display: block;" required/>
					</div>
					<div id="topic-field">
 						<label for="topic" style="display: block;">Topic</label>
						<input type="text" name="topic" class="text ui-widget-content ui-corner-all" style="display: block;" required/>
					</div>
					<div id="rating-field">
 						<label for="topic" style="display: block;">Rating</label>
						<input type="radio" name="rating" value="1" title="Extremely bad">Extremely bad</input>
				        <input type="radio" name="rating" value="2" title="Poor">Poor</input>
				        <input type="radio" name="rating" value="3" title="Average" checked="checked" >Average</input>
				        <input type="radio" name="rating" value="4" title="Good" >Good</input>
				      	<input type="radio" name="rating" value="5" title="Awesome" >Awesome</input>
					</div>
					<div id="contents-field">
						<label for="contents" style="display: block;">Contents</label>
						<textarea rows="1" id="contents" name="contents" class="text ui-widget-content ui-corner-all" required></textarea>					
					</div>
				</fieldset>
			</form>
		</div>