<?php
/**
* ReadyScript (http://readyscript.ru)
*
* @copyright Copyright (c) ReadyScript lab. (http://readyscript.ru)
* @license http://readyscript.ru/licenseAgreement/
*/
namespace Sitemap\Model;

class Api
{
    protected
        $site_id,
        $folder = '/storage/sitemap',
        $filename = 'sitemap-{site_id}.xml',
        $full_filename;
    
    function __construct($site_id = null)
    {
        if ($site_id) {
            \RS\Site\Manager::setCurrentSite(new \Site\Model\Orm\Site($site_id));
        }
        $this->site_id = \RS\Site\Manager::getSiteId();
        $this->full_filename = \Setup::$PATH.$this->folder.'/'.str_replace('{site_id}', $this->site_id, $this->filename);
    }
        
    /**
    * Отдает актуальный файл sitemap.xml на вывод
    * @return void
    */
    function sitemapToOutput()
    {
        if (!$this->checkActual()) {
            $this->generateSitemap();
        }
        $app = \RS\Application\Application::getInstance();        
        if (file_exists($this->full_filename)) {
            $app->headers
                ->addHeader('Content-Type', 'text/xml')
                ->sendHeaders();
            readfile($this->full_filename);
        } else {
            $app->showException(404, t('Файл не найден'));
        }
    }
    
    /**
    * Возвращает true, если файл sitemap существует и он актуальный
    * 
    * @return bool
    */
    function checkActual()
    {
        $config = \RS\Config\Loader::byModule($this);
        $expire_time = time() - $config['lifetime']*60;
        return $config['lifetime'] != 0 && (file_exists($this->full_filename) && filemtime($this->full_filename) > $expire_time);
    }
    
    /**
    * Создает файл sitemap.xml
    * 
    */
    function generateSitemap()
    {
        $event_result = \RS\Event\Manager::fire('getpages', array());
        $pagelist = $event_result->getResult();
        $config = \RS\Config\Loader::byModule($this);
        
        $additional_urls = $config['add_urls'];
        foreach(explode("\n", $additional_urls) as $url) {
            if ($url = trim($url)) {
                $pagelist[] = array(
                    'loc' => $url
                );
            }
        }
        
        $default_page = array(
            'priority' => $config['priority'],
        );
        if ($config['set_generate_time_as_lastmod']) {
            $default_page['lastmod'] = date('c');
        }
        if ($config['changefreq'] != 'disabled') {
            $default_page['changefreq'] = $config['changefreq'];
        }
        
        \RS\File\Tools::makePath($this->full_filename, true);
        
        $xml = new \XMLWriter();
        $xml->openURI($this->full_filename);
        $xml->startDocument('1.0', 'utf-8');
        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        
        $exclude_urls = !empty($config['exclude_urls']) ? explode("\r", $config['exclude_urls']) : array(); //url которые надо исключить
        
        $base_url = rtrim(\RS\Site\Manager::getSite()->getRootUrl(true), '/');
        foreach($pagelist as $page_data) {
            $page = array_intersect_key($page_data, array_flip(array('loc', 'lastmod', 'changefreq', 'priority'))) + $default_page;
            
            if (substr($page['loc'], 0, 4) != 'http') {
                $page['loc'] = $base_url.$page['loc'];
            }
            
            foreach ($exclude_urls as $exclude_url) {
                if (preg_match("/".trim(str_replace('/', '\/', $exclude_url))."/ui", $page['loc'])){   
                    continue 2;
                }  
            } 
            
            if (isset($page['loc'])) {
                $xml->startElement('url');
                foreach($page as $node => $value) {
                    $xml->writeElement($node, $value);
                }
                $xml->endElement();
            }
        }
        $xml->endDocument();
        $xml->flush();
        unset($xml);
    }
}