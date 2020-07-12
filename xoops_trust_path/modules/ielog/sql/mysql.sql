#
# Table structure for table `ielog_cat`
#

CREATE TABLE cat (
  cid int(5) unsigned NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  imgurl varchar(150) NOT NULL default '',
  kmlurl varchar(150) NOT NULL default '',
  weight int(5) unsigned NOT NULL default 0,
  depth int(5) unsigned NOT NULL default 0,
  description text,
  allowed_ext varchar(255) NOT NULL default 'jpg|jpeg|gif|png',
  lat double(9,6) NOT NULL default '0',
  lng double(9,6) NOT NULL default '0',
  zoom int(2) NOT NULL default '0',
  mtype varchar(30) NOT NULL default '',
  icd int(5) unsigned NOT NULL default '0',
  PRIMARY KEY (cid),
  KEY (weight),
  KEY (depth),
  KEY (pid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `ielog_photos`
#

CREATE TABLE photos (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  cid1 int(5) unsigned NOT NULL default '0',
  cid2 int(5) unsigned NOT NULL default '0',
  cid3 int(5) unsigned NOT NULL default '0',
  cid4 int(5) unsigned NOT NULL default '0',

  title varchar(255) NOT NULL default '',
  ext varchar(10) NOT NULL default '',
  res_x int(11) NOT NULL default '0',
  res_y int(11) NOT NULL default '0',
  caption varchar(255) NOT NULL default '',
  ext1 varchar(10) NOT NULL default '',
  res_x1 int(11) NOT NULL default '0',
  res_y1 int(11) NOT NULL default '0',
  caption1 varchar(255) NOT NULL default '',
  ext2 varchar(10) NOT NULL default '',
  res_x2 int(11) NOT NULL default '0',
  res_y2 int(11) NOT NULL default '0',
  caption2 varchar(255) NOT NULL default '',
  ext3 varchar(10) NOT NULL default '',
  res_x3 int(11) NOT NULL default '0',
  res_y3 int(11) NOT NULL default '0',
  caption3 varchar(255) NOT NULL default '',
  ext4 varchar(10) NOT NULL default '',
  res_x4 int(11) NOT NULL default '0',
  res_y4 int(11) NOT NULL default '0',
  caption4 varchar(255) NOT NULL default '',
  ext5 varchar(10) NOT NULL default '',
  res_x5 int(11) NOT NULL default '0',
  res_y5 int(11) NOT NULL default '0',
  caption5 varchar(255) NOT NULL default '',
  ext6 varchar(10) NOT NULL default '',
  res_x6 int(11) NOT NULL default '0',
  res_y6 int(11) NOT NULL default '0',
  caption6 varchar(255) NOT NULL default '',
  ext7 varchar(10) NOT NULL default '',
  res_x7 int(11) NOT NULL default '0',
  res_y7 int(11) NOT NULL default '0',
  caption7 varchar(255) NOT NULL default '',
  ext8 varchar(10) NOT NULL default '',
  res_x8 int(11) NOT NULL default '0',
  res_y8 int(11) NOT NULL default '0',
  caption8 varchar(255) NOT NULL default '',
  submitter int(11) unsigned NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  hits int(11) unsigned NOT NULL default '0',
  rating double(6,4) NOT NULL default '0.0000',
  votes int(11) unsigned NOT NULL default '0',
  comments int(11) unsigned NOT NULL default '0',
  poster_name varchar(60) NOT NULL default '',

  url varchar(255) NOT NULL default '',
  rss varchar(255) NOT NULL default '',
  tel varchar(255) NOT NULL default '',
  fax varchar(255) NOT NULL default '',
  zip varchar(255) NOT NULL default '',
  other1 varchar(255) NOT NULL default '',
  other2 varchar(255) NOT NULL default '',
  other3 varchar(255) NOT NULL default '',
  other4 varchar(255) NOT NULL default '',
  other5 varchar(255) NOT NULL default '',
  other6 varchar(255) NOT NULL default '',
  other7 varchar(255) NOT NULL default '',
  other8 varchar(255) NOT NULL default '',
  other9 varchar(255) NOT NULL default '',
  other10 varchar(255) NOT NULL default '',
  other11 varchar(255) NOT NULL default '',
  other12 varchar(255) NOT NULL default '',
  other13 varchar(255) NOT NULL default '',
  other14 varchar(255) NOT NULL default '',
  other15 varchar(255) NOT NULL default '',
  other16 varchar(255) NOT NULL default '',
  other17 varchar(255) NOT NULL default '',
  other18 varchar(255) NOT NULL default '',
  other19 varchar(255) NOT NULL default '',
  other20 varchar(255) NOT NULL default '',
  address varchar(255) NOT NULL default '',
  lat double(9,6) NOT NULL default '0',
  lng double(9,6) NOT NULL default '0',
  zoom int(2) NOT NULL default '0',
  mtype varchar(30) NOT NULL default '',
  icd int(5) unsigned NOT NULL default '0',

  PRIMARY KEY (lid),
  KEY (cid),
  KEY (date),
  KEY (status),
  KEY (title),
  KEY (submitter)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `ielog_icons`
#

CREATE TABLE icons (
  icd int(5) unsigned NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  ext varchar(5) NOT NULL default '',
  shadow_ext varchar(5) NOT NULL default '',
  x int(4) NOT NULL default '0',
  y int(4) NOT NULL default '0',
  shadow_x int(4) NOT NULL default '0',
  shadow_y int(4) NOT NULL default '0',
  Anchor_x int(4) NOT NULL default '0',
  Anchor_y int(4) NOT NULL default '0',
  infoWindowAnchor_x int(4) NOT NULL default '0',
  infoWindowAnchor_y int(4) NOT NULL default '0',
  PRIMARY KEY (icd)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `ielog_text`
#

CREATE TABLE text (
  lid int(11) unsigned NOT NULL default '0',
  description text,
  arrowhtml tinyint(1) NOT NULL default '0',
  addinfo text,
  PRIMARY KEY lid (lid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `ielog_votedata`
#

CREATE TABLE votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) unsigned NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY (lid),
  KEY (ratinguser),
  KEY (ratinghostname)
) TYPE=MyISAM;

