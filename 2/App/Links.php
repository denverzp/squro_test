<?php
namespace App;

/**
 * 
 */
class Links 
{
    /**
     * Target URI
     * @var string 
     */
    public $uri;
    
    /**
     * Target URI scheme
     * @var string 
     */
    public $scheme;
    
    /**
     * Target URI host
     * @var string 
     */
    public $host;
    
    /**
     * All links from URI
     * @var array 
     */
    public $find = [];
    
    /**
     * 
     * @param string $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
        
        extract(parse_url($uri));

        $this->scheme = $scheme;
        $this->host = $host;
    }
    
    /**
     * Load URI
     * @return string|bool
     */
    public function loadHTML()
    {
        return file_get_contents($this->uri);
    }

    /**
     * Find all links in loaded HTML
     * @param string $string
     * @return array
     */
    public function findLinks($string) 
    {
        $result = [];
        
        $regexp = "<a\s[^>]*href=([\"\']??)([^\" >]*?)\\1[^>]*>(:?.*)<\/a>";

        if (preg_match_all("/$regexp/siU", $string, $matches, PREG_SET_ORDER)) {

            foreach ($matches as $match) {
                
                if (!empty($match[2])) {

                    $link = $this->checkLink($match[2]);
                    
                    if (false === in_array($link, $result, false)) {

                        $result[] = $link;
                    
                    }
                }
            }
        }

        //unique values only
        $this->find = array_unique($result);
    }

    /**
     * Check links - convert relative to absolute URL
     * @param type $link
     * @return type
     */
    public function checkLink($link)
    {

        $regexp = "^(?:([^:\/?#]+):)?(?:\/\/([^\/?#]*))?(.*)?$";
        
        preg_match("/$regexp/si", $link, $parts);
        
        if(!isset($parts[1]) || empty($parts[1])){
            $parts[1] = $this->scheme;
        }
        
        if(!isset($parts[2]) || empty($parts[2])){
            $parts[2] = $this->host;
        }
        
        if(!isset($parts[3]) || empty($parts[3])){
            $parts[3] = '';
        }
        
        return preg_replace('/\/$/', '', $parts[1] . '://' . preg_replace('/\/+/', '/', $parts[2] . '/' . $parts[3]));
    }
}
