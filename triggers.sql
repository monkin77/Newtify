-- Trigger to update likes/dislikes of a content when feedback is given, 
-- creates a notification on that feedback and update user reputation
CREATE OR REPLACE FUNCTION feedback_content() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.is_like) THEN
        UPDATE "content" SET likes = likes + 1 WHERE id = NEW.content_id;
        
        UPDATE "authenticated_user" SET reputation = reputation + 1 
            WHERE id = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = NEW.content_id);

        INSERT INTO "notification"(date, receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) VALUES (CURRENT_TIMESTAMP, 1, FALSE, NULL, NEW.user_id, NULL, NULL, 'FEEDBACK');
    ELSE 
        UPDATE "content" SET dislikes = dislikes + 1 WHERE id = NEW.content_id;
        
        UPDATE "authenticated_user" SET reputation = reputation - 1 WHERE id = 
            (SELECT author_id 
             FROM content INNER JOIN authenticated_user ON (authenticated_user.id = content.author_id)
             WHERE content.id = NEW.content_id);
        
        INSERT INTO "notification"(date, receiver_id, is_read, msg, fb_giver, rated_content, new_comment, type) VALUES (CURRENT_TIMESTAMP, 1, FALSE, NULL, NEW.user_id, NULL, NULL, 'FEEDBACK');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS feedback_content ON "feedback";
CREATE TRIGGER feedback_content
    AFTER INSERT ON "feedback"
    FOR EACH ROW
    EXECUTE PROCEDURE feedback_content();


INSERT INTO "authenticated_user"(name, birth_date, password, is_suspended, reputation)
    VALUES ('rui', TO_TIMESTAMP('2001-03-23', 'YYYY-MM-DD'), '1234567', false, 0);

INSERT INTO "authenticated_user"(name, birth_date, password, is_suspended, reputation)
    VALUES ('bruno', TO_TIMESTAMP('2001-05-12', 'YYYY-MM-DD'), '1234567', false, 0);

INSERT INTO "content"(body, author_id) VALUES ('oi', 1);
INSERT INTO "content"(body, author_id) VALUES ('oi2', 2);

INSERT INTO "feedback"(user_id, content_id, is_like) VALUES (1, 2, True);
INSERT INTO "feedback"(user_id, content_id, is_like) VALUES (2, 1, False);

------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------

-- Trigger to remove like/dislike of a content when feedback on it is removed and to update authenticated user reputation
CREATE OR REPLACE FUNCTION remove_feedback() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (OLD.is_like) THEN
        UPDATE "content" SET likes = likes - 1 WHERE id = OLD.content_id;
        
        UPDATE "authenticated_user" SET reputation = reputation - 1 
            WHERE id = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = OLD.content_id);

    ELSE 
        UPDATE "authenticated_user" SET reputation = reputation + 1 
            WHERE id = (SELECT author_id FROM content INNER JOIN authenticated_user ON (content.author_id = authenticated_user.id) WHERE content.id = OLD.content_id);
        
        UPDATE "content" SET dislikes = dislikes - 1 WHERE id = OLD.content_id;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS remove_feedback ON "feedback";
CREATE TRIGGER remove_feedback
    AFTER DELETE ON "feedback"
    FOR EACH ROW
    EXECUTE PROCEDURE remove_feedback();

INSERT INTO "authenticated_user"(name, birth_date, password, is_suspended, reputation)
    VALUES ('rui', TO_TIMESTAMP('2001-03-23', 'YYYY-MM-DD'), '1234567', false, 0);

INSERT INTO "authenticated_user"(name, birth_date, password, is_suspended, reputation)
    VALUES ('bruno', TO_TIMESTAMP('2001-05-12', 'YYYY-MM-DD'), '1234567', false, 0);

INSERT INTO "content"(body, author_id) VALUES ('oi', 1);
INSERT INTO "content"(body, author_id) VALUES ('oi2', 2);

INSERT INTO "feedback"(user_id, content_id, is_like) VALUES (1, 2, True);
INSERT INTO "feedback"(user_id, content_id, is_like) VALUES (2, 1, False);

DELETE FROM "feedback" WHERE feedback.user_id = 1;
DELETE FROM "feedback" WHERE feedback.user_id = 2;

------------------------------------------------------------------------------------------------------------
------------------------------------------------------------------------------------------------------------

-- Trigger to prevent user from like or dislike his own content (articles or comments)
CREATE OR REPLACE FUNCTION check_feedback() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.user_id in (
        SELECT content.author_id 
        FROM content 
        WHERE content.id = NEW.content_id)) THEN
            RAISE EXCEPTION 'You cannot give feedback on your own content';
    END IF;
    RETURN NEW;
END;
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_feedback ON "feedback";
CREATE TRIGGER check_feedback
    BEFORE INSERT ON "feedback"
    FOR EACH ROW
    EXECUTE PROCEDURE check_feedback();


INSERT INTO "authenticated_user"(name, birth_date, password, is_suspended, reputation)
    VALUES ('rui', TO_TIMESTAMP('2001-03-23', 'YYYY-MM-DD'), '1234567', false, 0);

INSERT INTO "authenticated_user"(name, birth_date, password, is_suspended, reputation)
    VALUES ('jorge', TO_TIMESTAMP('2001-05-12', 'YYYY-MM-DD'), '1234567', false, 0);

INSERT INTO "content"(body, published_at, is_edited, likes, dislikes, author_id) VALUES ('oi', CURRENT_TIMESTAMP, false, 3, 2, 1);


INSERT INTO "feedback"(user_id, content_id, is_like) VALUES (1, 1, True);
INSERT INTO "feedback"(user_id, content_id, is_like) VALUES (2, 1, True);

----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

-- trigger to add notification when a message is sent form an user to another or to remove in case of being read
CREATE OR REPLACE FUNCTION message_sent_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (NEW.is_read) THEN
        DELETE FROM "notification" WHERE msg = NEW.id;
    ELSE 
        INSERT INTO "notification"(receiver_id, date, is_read, msg, fb_giver, rated_content, new_comment, type) 
            VALUES (NEW.receiver_id, NEW.published_at, FALSE, NEW.id, NULL, NULL, NULL, 'MESSAGE');
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;


DROP TRIGGER IF EXISTS message_sent_notification ON "message";
CREATE TRIGGER message_sent_notification
    AFTER INSERT ON "message"
    FOR EACH ROW
    EXECUTE PROCEDURE message_sent_notification();


INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('rui', 'rui@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('joao', 'joao@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('forever', 'forever@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "message"(body, published_at, sender_id, receiver_id, is_read) VALUES ('oi', CURRENT_TIMESTAMP, 1, 2, FALSE);
INSERT INTO "message"(body, published_at, sender_id, receiver_id, is_read) VALUES ('oioi', CURRENT_TIMESTAMP, 2, 3, FALSE);


----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

-- trigger to prevent user to delete a comment or article (content) with likes or dislikes or with subcomments
CREATE OR REPLACE FUNCTION check_content_delete() RETURNS TRIGGER AS
$BODY$
BEGIN 
    IF (OLD.likes != 0 or OLD.dislikes != 0) THEN
        RAISE EXCEPTION 'You cannot delete a content that has likes/dislikes';
    ELSE 
        IF (OLD.id in (SELECT article_id FROM comment WHERE comment.content_id = OLD.id) OR 
            OLD.id in (SELECT parent_comment_id FROM comment WHERE comment.parent_comment_id = OLD.id)) THEN -- is an article with comments or is a comment with sub comments
            RAISE EXCEPTION 'You cannot delete a content that has comments';
        ELSE 
            DELETE FROM "content" WHERE content.id = OLD.id;
        END IF;
    END IF;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_content_delete ON "content";
CREATE TRIGGER check_content_delete
    BEFORE DELETE ON "content"
    FOR EACH ROW
    EXECUTE PROCEDURE check_content_delete();


INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('rui', 'rui@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('joao', 'joao@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('forever', 'forever@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "content"(body, published_at, is_edited, likes, dislikes, author_id) VALUES ('oi artigo', CURRENT_TIMESTAMP, false, 5, 1, 1);
INSERT INTO "article"(content_id, title) VALUES (1, 'Article');

INSERT INTO "content"(body, published_at, is_edited, likes, dislikes, author_id) VALUES ('ola filho', CURRENT_TIMESTAMP, false, 0, 0, 2);
INSERT INTO "comment"(content_id, article_id, parent_comment_id) VALUES (2, 1, NULL);

INSERT INTO "content"(body, published_at, is_edited, likes, dislikes, author_id) VALUES ('ola pai', CURRENT_TIMESTAMP, false, 3, 1, 3);
INSERT INTO "comment"(content_id, article_id, parent_comment_id) VALUES (3, 1, 2);

INSERT INTO "content"(body, author_id) VALUES ('tentar apagar', 3);

DELETE FROM "content" WHERE content.id = 5; -- in case we just insert this one from line behind

----------------------------------------------------------------------------------------------------------------


-- trigger to delete all the information about an article that was deleted
-- it just needs to delete the content represented by that article 
-- since its that deletion is cascaded to the comments and other elements of the article
CREATE OR REPLACE FUNCTION delete_article() RETURNS TRIGGER AS
$BODY$
BEGIN 
    DELETE FROM content WHERE content.id = OLD.content_id;
    RETURN NULL;
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS delete_article ON "article";
CREATE TRIGGER delete_article
    BEFORE DELETE ON "article"
    FOR EACH ROW
    EXECUTE PROCEDURE delete_article();

-- need to test this in order to check the cascade

----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

CREATE OR REPLACE FUNCTION check_add_article_tag() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF (COUNT(SELECT article_id FROM article_tag WHERE NEW.content_id = article_tag.article_id) >= 3)) THEN 
        RAISE EXCEPTION 'You cannot put more tags on this article since already has 3 (limit of tags)';
    ELSE INSERT INTO "article_tag"(article_id, tag_id) VALUES (NEW.article_id, NEW.tag_id);
END
$BODY$

LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS check_add_article_tag ON "article_tag";
CREATE TRIGGER check_add_article_tag
    BEFORE INSERT ON "article_tag"
    FOR EACH ROW
    EXECUTE PROCEDURE check_add_article_tag();

----------------------------------------------------------------------------------------------------------------
----------------------------------------------------------------------------------------------------------------

-- create a trigger to check if an article already has 3 tags

-- TRIGGER FOREVER 

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('rui', 'rui@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('joao', 'joao@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "content" (body, author_id) VALUES ('oi', 1);

INSERT INTO "content" (body, author_id) VALUES ('oi2', 2);

UPDATE lbaw2111.content
    SET body='AAAAAAAAAAAAAAAAAAAAAAAAA'
    WHERE id = 1;

INSERT INTO "article"(content_id, title) VALUES (2, 'title1');



-- TRIGGER JORGE

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('rui', 'rui@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);

INSERT INTO "authenticated_user"(name, email, birth_date, password, is_suspended, reputation) 
    VALUES ('joao', 'joao@gmail.com', CURRENT_TIMESTAMP, '1234567', false, 0);  

INSERT INTO "content" (body, author_id) VALUES ('oi', 1);

INSERT INTO "content" (body, author_id) VALUES ('oi2', 2);

INSERT INTO "article"(content_id, title) VALUES (2, 'title1');

INSERT INTO "tag"(name, state, user_id) VALUES ('desporto', 'ACCEPTED', 1);
INSERT INTO "tag"(name, state, user_id) VALUES ('anime', 'ACCEPTED', 1);
INSERT INTO "tag"(name, state, user_id) VALUES ('ciencia', 'ACCEPTED', 1);
INSERT INTO "tag"(name, state, user_id) VALUES ('fantasia', 'ACCEPTED', 1);
INSERT INTO "article_tag"(article_id, tag_id) VALUES  (2, 1);
INSERT INTO "article_tag"(article_id, tag_id) VALUES  (2, 2);
INSERT INTO "article_tag"(article_id, tag_id) VALUES  (2, 3);
INSERT INTO "article_tag"(article_id, tag_id) VALUES  (2, 4);


 
