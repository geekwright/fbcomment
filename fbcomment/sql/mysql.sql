#
# Tables for fbcomment module  @version    $Id$
#

CREATE TABLE fbc_og_meta (
  id int(10) NOT NULL auto_increment,
  url varchar(767) CHARACTER SET ascii  NOT NULL,
  image varchar(1024),
  title varchar(255),
  description varchar(1024),
  lastupdate  int(10) NOT NULL default '0',

  PRIMARY KEY (id),
  UNIQUE INDEX (url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE fbc_like_tracker (
  id int(10) NOT NULL auto_increment,
  url varchar(767) CHARACTER SET ascii  NOT NULL,
  count int(10) NOT NULL default '1',
  lastlike  int(10) NOT NULL default '0',
  firstlike  int(10) NOT NULL default '0',

  PRIMARY KEY (id),
  UNIQUE INDEX (url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE fbc_comment_tracker (
  id int(10) NOT NULL auto_increment,
  url varchar(767) CHARACTER SET ascii  NOT NULL,
  count int(10) NOT NULL default '1',
  lastcomment  int(10) NOT NULL default '0',
  firstcomment  int(10) NOT NULL default '0',

  PRIMARY KEY (id),
  UNIQUE INDEX (url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

