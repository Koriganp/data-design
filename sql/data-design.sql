DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS post;
DROP TABLE IF EXISTS profile;

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
	postContent VARCHAR(3000) NOT NULL,
	postDate DATETIME(6) NOT NULL,
	INDEX (postProfileId),
	FOREIGN KEY(postProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(postId)
);

CREATE TABLE comments (
	commentsId BINARY(16) NOT NULL,
	commentsProfileId BINARY(16) NOT NULL,
	commentsPostId BINARY(16) NOT NULL,
	commentsCommentsId BINARY(16),
	commentsContent VARCHAR(3000) NOT NULL,
	commentsDate DATETIME(6) NOT NULL,
	INDEX(commentsProfileId),
	INDEX(commentsPostId),
	FOREIGN KEY(commentsProfileId) REFERENCES profile(profileId),
	FOREIGN KEY(commentsPostId) REFERENCES post(postId),
	FOREIGN KEY (commentsCommentsId) REFERENCES comments(commentsId),
	PRIMARY KEY(commentsId)
);
