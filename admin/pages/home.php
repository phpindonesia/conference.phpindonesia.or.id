<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="style/style.css">
	<link rel="stylesheet" href="plugins/tabs/css/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="plugins/tabs/css/style.css"> <!-- Resource style -->
	<script src="plugins/tabs/js/modernizr.js"></script> <!-- Modernizr -->


<div class="cd-tabs">
	<nav>
		<ul class="cd-tabs-navigation">
			<li><a data-content="inbox" class="selected" href="#0">Inbox</a></li>
			<li><a data-content="acara" href="#0">Acara</a></li>
			<li><a data-content="about" href="#0">About</a></li>
			<li><a data-content="settings" href="#0">Settings</a></li>
			<li><a data-content="trash" href="#0">Trash</a></li>
		</ul> <!-- cd-tabs-navigation -->
	</nav>

	<ul class="cd-tabs-content">
		<li data-content="inbox" class="selected">
		<?php include 'i_inbox.php'; ?>
		</li>

		<li data-content="acara">
		<?php include 'i_acara.php'; ?>
		</li>

		<li data-content="about">
		<?php include 'i_about.php'; ?>
		</li>

		<li data-content="settings">
			<p>Settings Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laboriosam nam magni, ullam nihil a suscipit, ex blanditiis, adipisci tempore deserunt maiores. Nostrum officia, ratione enim eaque nihil quis ea, officiis iusto repellendus. Animi illo in hic, maxime deserunt unde atque a nesciunt? Non odio quidem deserunt animi quod impedit nam, voluptates eum, voluptate consequuntur sit vel, et exercitationem sint atque dolores libero dolorem accusamus ratione iste tenetur possimus excepturi. Accusamus vero, dignissimos beatae tempore mollitia officia voluptate quam animi vitae.</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique ipsam eum reprehenderit minima at sapiente ad ipsum animi doloremque blanditiis unde omnis, velit molestiae voluptas placeat qui provident ab facilis.</p>
		</li>

		<li data-content="trash">
			<p>Trash Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio itaque a iure nostrum animi praesentium, numquam quidem, nemo, voluptatem, aspernatur incidunt. Fugiat aspernatur excepturi fugit aut, dicta reprehenderit temporibus, nobis harum consequuntur quo sed, illum.</p>

			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima doloremque optio tenetur, natus voluptatum error vel dolorem atque perspiciatis aliquam nemo id libero dicta est saepe laudantium provident tempore ipsa, accusamus similique laborum, consequatur quia, aut non maiores. Consectetur minus ipsum aliquam pariatur dolorem rerum laudantium minima perferendis in vero voluptatem suscipit cum labore nemo explicabo, itaque nobis debitis molestias officiis? Impedit corporis voluptates reiciendis deleniti, magnam, fuga eveniet! Velit ipsa quo labore molestias mollitia, quidem, alias nisi architecto dolor aliquid qui commodi tempore deleniti animi repellat delectus hic. Alias obcaecati fuga assumenda nihil aliquid sed vero, modi, voluptatem? Vitae voluptas aperiam nostrum quo harum numquam earum facilis sequi. Labore maxime laboriosam omnis delectus odit harum recusandae sint incidunt, totam iure commodi ducimus similique doloremque! Odio quaerat dolorum, alias nihil quam iure delectus repellendus modi cupiditate dolore atque quasi obcaecati quis magni excepturi vel, non nemo consequatur, mollitia rerum amet in. Nesciunt placeat magni, provident tempora possimus ut doloribus ullam!</p>
		</li>
	</ul> <!-- cd-tabs-content -->
</div> <!-- cd-tabs -->
<script src="plugins/texteditor/tinymce.min.js"></script>
<script>tinymce.init({selector:'textarea',height : "300px"});</script>
<script src="plugins/tabs/js/jquery-2.1.1.js"></script>
<script src="plugins/tabs/js/main.js"></script> <!-- Resource jQuery -->
<script type="text/javascript" src="js/j_post_about.js"></script>
<script type="text/javascript" src="js/j_post_acara.js"></script>


