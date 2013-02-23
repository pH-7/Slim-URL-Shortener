--
--
-- Author:        Pierre-Henry Soria <ph7software@gmail.com>
-- Copyright:     (c) 2013, Pierre-Henry Soria. All Rights Reserved.
-- Link:          http://github.com/pH-7/Slim-URL-Shortener
-- License:       GNU General Public License <http://www.gnu.org/licenses/gpl.html>
--
--

CREATE DATABASE shorturl DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE shorturl;

CREATE TABLE IF NOT EXISTS phs_model_url (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  createdDate datetime NOT NULL,
  link varchar(150) NOT NULL,
  ip varchar(20) NOT NULL DEFAULT '127.0.0.1',
  nb_access int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY (link),
  INDEX (link)
) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
