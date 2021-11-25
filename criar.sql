SET search_path TO lbaw2111;

-----------------------------------------
-- Drop old schema
-----------------------------------------
DROP DOMAIN IF EXISTS EMAIL CASCADE;

DROP TYPE IF EXISTS PROPOSED_TAG_STATES CASCADE;
DROP TYPE IF EXISTS NOTIFICATION_TYPE;

DROP TABLE IF EXISTS "authenticated_user" CASCADE;
DROP TABLE IF EXISTS "suspension" CASCADE;
DROP TABLE IF EXISTS "report" CASCADE;
DROP TABLE IF EXISTS "country" CASCADE;
DROP TABLE IF EXISTS "tag" CASCADE;
DROP TABLE IF EXISTS "area_of_expertise" CASCADE;
DROP TABLE IF EXISTS "favorite_tag" CASCADE;
DROP TABLE IF EXISTS "proposed_tag" CASCADE;
DROP TABLE IF EXISTS "message" CASCADE;
DROP TABLE IF EXISTS "follow" CASCADE;
DROP TABLE IF EXISTS "content" CASCADE;
DROP TABLE IF EXISTS "article" CASCADE;
DROP TABLE IF EXISTS "comment" CASCADE;
DROP TABLE IF EXISTS "feedback" CASCADE;
DROP TABLE IF EXISTS "article_tag" CASCADE;
DROP TABLE IF EXISTS "notification" CASCADE;
DROP TABLE IF EXISTS "message_notification" CASCADE;
DROP TABLE IF EXISTS "feedback_notification" CASCADE;
DROP TABLE IF EXISTS "comment_notification" CASCADE;

-----------------------------------------
-- DOMAINS
-----------------------------------------

CREATE DOMAIN VALID_EMAIL AS TEXT CHECK(VALUE LIKE '_%@_%.__%');

-----------------------------------------
-- TYPES
-----------------------------------------

CREATE TYPE PROPOSED_TAG_STATES AS ENUM ('PENDING', 'ACCEPTED', 'REJECTED');
CREATE TYPE NOTIFICATION_TYPE AS ENUM ('MESSAGE', 'FEEDBACK', 'COMMENT');

-----------------------------------------
-- Tables
-----------------------------------------

CREATE TABLE "country" (
  id SERIAL PRIMARY KEY,
  code TEXT NOT NULL UNIQUE,
  name TEXT NOT NULL UNIQUE
);

-----------------------------------------


CREATE TABLE "authenticated_user" (
  id SERIAL PRIMARY KEY, 
  name TEXT NOT NULL, 
  email VALID_EMAIL NOT NULL UNIQUE, 
  birth_date TIMESTAMP NOT NULL CHECK (CURRENT_TIMESTAMP >= birth_date),
  admin BOOLEAN DEFAULT false,
  description TEXT, 
  password TEXT NOT NULL, 
  avatar TEXT, 
  city TEXT, 
  is_suspended BOOLEAN NOT NULL,
  reputation INTEGER NOT NULL DEFAULT 0,
  country_id INTEGER REFERENCES "country"(id) ON DELETE CASCADE ON UPDATE CASCADE
);


-----------------------------------------

CREATE TABLE "suspension" (
  id SERIAL PRIMARY KEY,
  reason TEXT NOT NULL,
  start_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  end_time TIMESTAMP NOT NULL CHECK (end_time >= start_time),
  admin_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  user_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE
  CONSTRAINT diff_entities CHECK (admin_id != user_id)
);

-----------------------------------------

CREATE TABLE "report"(
  id SERIAL PRIMARY KEY, 
  reason TEXT NOT NULL, 
  reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  is_closed BOOLEAN DEFAULT false, 
  reported_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  reporter_id INTEGER REFERENCES "authenticated_user"(id) ON UPDATE CASCADE,
  CONSTRAINT different_ids CHECK (reporter_id != reported_id)
);

-----------------------------------------

CREATE TABLE "tag" (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL UNIQUE,
  proposed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  state PROPOSED_TAG_STATES NOT NULL DEFAULT 'PENDING',
  user_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-----------------------------------------

CREATE TABLE "area_of_expertise"(
  user_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id  INTEGER REFERENCES "tag"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  reputation INTEGER NOT NULL,
  PRIMARY KEY (user_id, tag_id)
);

-----------------------------------------

CREATE TABLE "favorite_tag"(
  user_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id  INTEGER REFERENCES "tag"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY (user_id, tag_id)
);


-----------------------------------------

CREATE TABLE "message"(
  id SERIAL PRIMARY KEY,
  body TEXT NOT NULL,
  published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  sender_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  receiver_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  is_read BOOLEAN DEFAULT false
);

-----------------------------------------

CREATE TABLE "follow"(
  follower_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  followed_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY(follower_id, followed_id)
);

-----------------------------------------

CREATE TABLE "content"(
  id SERIAL PRIMARY KEY,
  body TEXT NOT NULL,
  published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_edited BOOLEAN DEFAULT false,
  likes INTEGER CHECK (likes >= 0),
  dislikes INTEGER CHECK (dislikes >= 0),
  author_id INTEGER NOT NULL REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE -- trigger must update user instead of deleting it
);

-----------------------------------------

CREATE TABLE "article" (
  content_id INTEGER PRIMARY KEY REFERENCES "content"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  title TEXT NOT NULL, 
  thumbnail TEXT
);

-----------------------------------------

CREATE TABLE "comment"(
  content_id INTEGER PRIMARY KEY REFERENCES "content"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  article_id INTEGER NOT NULL REFERENCES "article"(content_id) ON DELETE CASCADE ON UPDATE CASCADE,
  parent_comment_id INTEGER REFERENCES "comment"(content_id) ON UPDATE CASCADE
);

-----------------------------------------

CREATE TABLE "feedback"(
  user_id INTEGER REFERENCES "authenticated_user"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  content_id INTEGER REFERENCES "content"(id) ON DELETE CASCADE ON UPDATE CASCADE, 
  is_like BOOLEAN NOT NULL,
  PRIMARY KEY (user_id, content_id)
);

-----------------------------------------

CREATE TABLE "article_tag"(
  article_id INTEGER REFERENCES "article"(content_id) ON DELETE CASCADE ON UPDATE CASCADE,
  tag_id INTEGER REFERENCES "tag"(id) ON DELETE CASCADE ON UPDATE CASCADE,
  PRIMARY KEY(article_id, tag_id)
);

-----------------------------------------

CREATE TABLE "notification"(
  id SERIAL PRIMARY KEY, 
  date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  is_read BOOLEAN DEFAULT false,
  msg INTEGER REFERENCES "message"(id),
  fb_giver INTEGER REFERENCES "authenticated_user"(id),
  rated_content INTEGER REFERENCES "content"(id),
  new_comment INTEGER REFERENCES "comment"(content_id),
  type NOTIFICATION_TYPE NOT NULL
);

-----------------------------------------
