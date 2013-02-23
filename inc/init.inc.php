<?php
/**
 * @author           Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright        (c) 2013, Pierre-Henry Soria. All Rights Reserved.
 * @link             http://github.com/pH-7/Slim-URL-Shortener
 * @license          GNU General Public License <http://www.gnu.org/licenses/gpl.html>
 */

namespace PHS;

defined('PHS') or die('Forbidden acces');


// Start session
session_cache_limiter(false);
session_start();

require VENDOR_PATH . 'Slim/Slim.php';
require VENDOR_PATH . 'Db/idiorm.php';
require VENDOR_PATH . 'Db/paris.php';
require __DIR__ . '/config.inc.php';
require __DIR__ . '/Model/Url.php';
