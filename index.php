<?php
/**
 * @author           Pierre-Henry Soria <ph7software@gmail.com>
 * @copyright        (c) 2013, Pierre-Henry Soria. All Rights Reserved.
 * @link             http://github.com/pH-7/Slim-URL-Shortener
 * @license          GNU General Public License <http://www.gnu.org/licenses/gpl.html>
 */

namespace PHS;

use Slim\Slim, Slim\Extras\Views\Twig, PHS\Model\Url;

define('PHS', 1);


require 'constants.php';
require ROOT_PATH . 'inc/init.inc.php';

putenv('LC_ALL=' . DEFAULT_LANG);
setlocale(LC_ALL, DEFAULT_LANG);

bindtextdomain(LANG_DOMAIN, LANG_PATH);
bind_textdomain_codeset(LANG_DOMAIN, ENCODING);

// Choose domain
textdomain(LANG_DOMAIN);

Slim::registerAutoloader();

// Views
Twig::$twigDirectory = VENDOR_PATH . '/Twig/';

Twig::$twigOptions = array('debug' => DEBUG);

if (is_writable(TPL_CACHE))
    Twig::$twigOptions['cache'] = TPL_CACHE;

Twig::$twigExtensions = array(
    'Twig_Extensions_Slim',
    'Twig_Extension_Debug',
    'Twig_Extensions_Extension_I18n'
);


$oApp = new Slim(array(
    'debug' => DEBUG,
    'locales.path' => LANG_PATH,
    'view' => new Twig,
    'templates.path' => 'views'
));

define('ROOT_URL', $oApp->request()->getUrl() . $oApp->request()->getRootUri());

// Models
\ORM::configure( sprintf('mysql:host=%s;dbname=%s', DB_HOSTNAME, DB_NAME) );
\ORM::configure('username', DB_USR);
\ORM::configure('password', DB_PWD);
\Model::$auto_prefix_models = '\\PHS\Model\\';

$aViewParams = array('encoding' => ENCODING, 'lang' => DEFAULT_LANG, 'url' => ROOT_URL, 'style_name' => STYLE_NAME);

// URLs list
$oApp->get('/', function() use ($oApp)
{
    $oUrls = \Model::factory('Url');
    $oAllUrls = $oUrls->order_by_desc('createdDate')->find_many();
    $iTotalUrls =  $oUrls->count();

    global $aViewParams;
    $_aViewParams = $aViewParams + array('urls' => $oAllUrls, 'total_urls' => $iTotalUrls);  // Update array
    return $oApp->render('list.twig', $_aViewParams);
});

// Add URL
$oApp->get('/add', function() use ($oApp)
{
    global $aViewParams;
    return $oApp->render('add.twig', $aViewParams);
});

// Add URL in submit form
$oApp->post('/add', function() use ($oApp)
{
    $oUrls = \Model::factory('Url');
    $sLink = $oApp->request()->post('link');

    // Check link
    if (!filter_var($sLink, FILTER_VALIDATE_URL))
    {
        $oApp->flash('error', gettext('Invalid link!'));
        $oApp->redirect(ROOT_URL);
    }
    else
    {
        $oLink = $oUrls->where('link', $sLink);
        if ($oLink->count() < 1)
        {
            $oUrl = $oUrls->create();
            $oUrl->link = $sLink;
            $oUrl->createdDate = date('Y-m-d H:i:s');
            $oUrl->ip = $oApp->request()->getIp();
            $oUrl->save();
        }
        $oApp->flash('success', sprintf(gettext('The shortened URL is available at: %s'), ROOT_URL . '/' . $oLink->find_many()[0]->id));
        $oApp->redirect(ROOT_URL); // Go to the list of links
    }
});

// Contact
$oApp->get('/contact', function() use ($oApp) {
    global $aViewParams;
    return $oApp->render('contact.twig', $aViewParams);
});

// Redirect ID towards the URL
$oApp->get('/:id', function($iId) use ($oApp) {
    $oUrl = \Model::factory('Url')->find_one($iId);
    if (! $oUrl instanceof Url) {
        $oApp->notFound();
    } else {
        $oUrl->nb_access += 1; // Count the number of visit for the link
        $oUrl->save();
        $oApp->redirect($oUrl->link);
    }
});

// GO!
$oApp->run();
