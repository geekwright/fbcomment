#
#

CREATE TABLE fbc_og_meta (
  id          INT(10)             NOT NULL AUTO_INCREMENT,
  url         VARCHAR(767)
              CHARACTER SET ascii NOT NULL,
  image       VARCHAR(1024),
  title       VARCHAR(255),
  description VARCHAR(1024),
  lastupdate  INT(10)             NOT NULL DEFAULT '0',

  PRIMARY KEY (id),
  UNIQUE INDEX (url)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE fbc_like_tracker (
  id        INT(10)             NOT NULL AUTO_INCREMENT,
  url       VARCHAR(767)
            CHARACTER SET ascii NOT NULL,
  count     INT(10)             NOT NULL DEFAULT '1',
  lastlike  INT(10)             NOT NULL DEFAULT '0',
  firstlike INT(10)             NOT NULL DEFAULT '0',

  PRIMARY KEY (id),
  UNIQUE INDEX (url)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE fbc_comment_tracker (
  id           INT(10)             NOT NULL AUTO_INCREMENT,
  url          VARCHAR(767)
               CHARACTER SET ascii NOT NULL,
  count        INT(10)             NOT NULL DEFAULT '1',
  lastcomment  INT(10)             NOT NULL DEFAULT '0',
  firstcomment INT(10)             NOT NULL DEFAULT '0',

  PRIMARY KEY (id),
  UNIQUE INDEX (url)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

