<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8"/>
		<meta name="author" content="Korigan Payne"/>
		<meta name="description" content="Data Design Project"/>
		<link href="styles/style.css" rel="stylesheet" type="text/css"/>
		<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"/>
		<title>Data Design Project</title>
	</head>
	<body>
		<main>
			<h1>Persona</h1>
			<img id="jared" src="images/persona.jpg" alt="Picture of Jared Mason"/>
			<p>Jared Mason is a 32 year old businessman who works for a large marketing firm. His work requires him to spend
			several days a week in different cities across the US, and sometimes overseas. He loves his job, and enjoys helping
			his clients reach the new customers in markets that they normally wouldn't appeal to. He is a real go-getter, with
			a no-nonsense attitude.</p>
			<p>Jared also enjoys taking his clients out to extravagant places to eat, because he is also a foodie. He loves fine
			dining as well as the low-key places that no one really knows about but the food loving community. Jared also likes
			night life and enjoys meeting new people and having a good time.</p>
			<p>Because Mr. Mason is often in places that he hasn't been or has only been to a few times, he wants a way to
			communicate with other food enthusiasts in what ever city he is traveling to that week. He wants to find locations
			to treat his clients to that they might not even know about. He wants to get information about the restaurants before
			he arrives and be able to inspect the establishment before he takes his clients there. He wants something that would
			provide both user insight and opinions as well as reviews of the place recommended.</p>
			<p>Jared is also looking for a place to meet people that share his interests when he is not working. He wants to
			know where the classier places to frequent are, and if there is an opinion of the residents of the city which is
			the best for his personality and interests.</p>
			<h2>Frustrations</h2>
			<ul>
				<li>Wants something easy to search, post to, and make comments</li>
				<li>Slow loading speed</li>
				<li>Lack of 4G coverage</li>
			</ul>

			<h1>User Story</h1>
			<p>A site user looking for a nice restaurant in Albuquerque.</p>

			<h1>Use Case/Interaction Flow</h1>
			<div class="centerList">
				<ul>
					<li>Jared creates a post</li>
					<li>Site displays post</li>
					<li>User X comments on his post</li>
					<li>Site displays comment on post</li>
					<li>User Y comments on User X's comment</li>
					<li>Site displays User Y's comment</li>
					<li>User Z comments on Jared's post</li>
					<li>Site displays comment on post</li>
				</ul>
			</div>

			<h1>Conceptual Model</h1>
			<div class="centerList">
				<ul>
					<li>One user can post many times</li>
					<li>Many users can comment on many posts</li>
					<li>Many users can comment on many comments</li>
				</ul>
				<h2>Profile</h2>
				<ul>
					<li>profileId (primary key)</li>
					<li>profileUserName</li>
					<li>profileActivationToken</li>
					<li>profileEmail</li>
					<li>profileHash</li>
					<li>profileSalt</li>
				</ul>
				<h2>Post</h2>
				<ul>
					<li>postId (primary key)</li>
					<li>postProfileId (foreign key)</li>
					<li>postContent</li>
					<li>postDate</li>
				</ul>
				<h2>Comment</h2>
				<ul>
					<li>commentProfileId (foreign key)</li>
					<li>commentPostId (foreign key)</li>
					<li>commentDate</li>
				</ul>

			</div>

		</main>
	</body>
</html>