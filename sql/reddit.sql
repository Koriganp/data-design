-- DROP TABLE IF EXISTS profile;
-- DROP TABLE IF EXISTS post;
-- DROP TABLE IF EXISTS comments;

CREATE TABLE profile (
	profileId BINARY(16) NOT NULL ,
	profileActivationToken CHAR(32),
	profileUserName VARCHAR(128) NOT NULL,
	profileEmail VARCHAR(128) NOT NULL,
	profileHash CHAR(128) NOT NULL,
	profileSalt CHAR(64) NOT NULL,
	UNIQUE(profileEmail),
	UNIQUE(profileUserName),
	PRIMARY KEY(profileId)
);

CREATE TABLE post (
	postId BINARY(16) NOT NULL,
	postProfileId BINARY(16) NOT NULL,
	postContent VARCHAR(65535) NOT NULL,
	postDate DATETIME(6) NOT NULL,
	INDEX (postProfileId),
	FOREIGN KEY(postProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(postId)
);

CREATE TABLE comments (
	commentsId BINARY(16) NOT NULL,
	commentsProfileId BINARY(16) NOT NULL,
	commentsPostId BINARY(16) NOT NULL,
	commentsContent VARCHAR(65535) NOT NULL,
	commentsDate DATETIME(6) NOT NULL,
	INDEX(commentsProfileId),
	INDEX(commentsPostId),
	FOREIGN KEY(commentsProfileId) REFERENCES profile(profileId),
	FOREIGN KEY(commentsPostId) REFERENCES post(postId),
	PRIMARY KEY(commentsId)
);

INSERT INTO profile(profileId, profileActivationToken, profileUserName, profileEmail, profileHash, profileSalt)
	VALUES(UNHEX(REPLACE('9f9a3997-1ef4-4ae7-9a87-5c28fe6cbefc', '-', '')), '8e56884b8ca4fa0216cd8bc898f4229a', 'JaredM', 'JaredM@gmail.com',
			 '1a935eae541e5886c86b8f026481aefe33509f99a71d802f33c6d6bd4a32871b3faf54ad92d98a2a6f4073841a1d61f2962f6cc1881106a2f01721958bccaf1f',
			 '22f614639f0f08d72b879071f37e73f26f8872e18901d3542a66c3b8ab7b920d'
);

INSERT INTO post(postId, postProfileId, postContent, postDate)
	VALUES (UNHEX(REPLACE('ab1af7cf-7ffc-48e1-b7da-95f8fe8f7b68', '-', '')), UNHEX(REPLACE('9f9a3997-1ef4-4ae7-9a87-5c28fe6cbefc', '-', '')),
	'This is a post', '20171017'
);

INSERT INTO comments(commentsId, commentsProfileId, commentsPostId, commentsContent, commentsDate)
	VALUES (UNHEX(REPLACE('4a1e57df-5b2b-4ef8-a9f6-38988d8db707', '-', '')), UNHEX(REPLACE('9f9a3997-1ef4-4ae7-9a87-5c28fe6cbefc', '-', '')),
	UNHEX(REPLACE('ab1af7cf-7ffc-48e1-b7da-95f8fe8f7b68', '-', '')), 'This is a comment', '20171017'
	);

