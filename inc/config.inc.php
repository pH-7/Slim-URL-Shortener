<?php
/**
 * @author           Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright        (c) 2013, Pierre-Henry Soria. All Rights Reserved.
 * @link             http://github.com/pH-7/Slim-URL-Shortener
 * @license          GNU General Public License <http://www.gnu.org/licenses/gpl.html>
 */

namespace PHS;

defined('PHS') or die('Forbidden acces');


define('DEBUG', false); // FALSE = Development mode | TRUE = Production mode
define('STYLE_NAME', 'classic');
define('DEFAULT_LANG', 'en_US');
define('LANG_DOMAIN', 'shorturl');
define('ENCODING', 'utf-8');

// Database info
define('DB_HOSTNAME', 'localhost');
define('DB_NAME', 'shorturl');
define('DB_USR', 'root');
define('DB_PWD', '');
