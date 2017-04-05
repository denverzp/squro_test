<?php
namespace Tests;

require_once(__DIR__ . '/../vendor/autoload.php');

use \PHPUnit\Framework\TestCase;
use \App\Links;

class LinksTest extends TestCase
{
    
    /**
     * @dataProvider addLink
     * @param type $source
     * @param type $result
     */
    public function testCheckLink($source, $result)
    {
        $links = new Links('http://a');
        
        $link = $links->checkLink($source);
        
        $this->assertEquals($result, $link);
    }
    
    public function addLink()
    {
        return [
            ['http://a/b/', 'http://a/b' ],
            ['https://a/b/', 'https://a/b' ],
            ['http://a/b/c/', 'http://a/b/c' ],
            ['http://a/b/c/?f#a', 'http://a/b/c/?f#a' ],
            ['//a/b/c/?f#a', 'http://a/b/c/?f#a' ],
            ['/b/c/?f#a', 'http://a/b/c/?f#a' ],
            ['g',   'http://a/g'],
            ['g/',  'http://a/g'],
            ['/g',  'http://a/g'],
            ['//g', 'http://g'],
            ['g?y', 'http://a/g?y'],
            ['#s', 'http://a/#s'],
            ['g#s', 'http://a/g#s'],
            ['g?y#s', 'http://a/g?y#s'],
            [';x', 'http://a/;x'],
            ['g;x', 'http://a/g;x'],
            ['g;x?y#s', 'http://a/g;x?y#s'],
        ];
    }
    
    /**
     * @dataProvider addHTMLString
     * @param type $source
     * @param type $result
     */
    public function testFindLinks($source, $result) 
    {
        $links = new Links('http://link.com');
        
        $links->findLinks($source);
        
        $this->assertEquals($result, $links->find[0]);
    }
    
    
    public function addHTMLString()
    {
        return [
            ['Lorem ipsum <a href="http://link.com"> sit amet</a>', 'http://link.com' ],
            ['Lorem ipsum <a href="https://link.com"> sit amet</a>', 'https://link.com' ],
            ['Lorem ipsum <a href=\'http://link.com\'> sit amet</a>', 'http://link.com'],
            ['Lorem ipsum <a href="http://link.com" data-id="some"> sit amet</a>', 'http://link.com'],
            ['Lorem ipsum <a data-id="some" href="http://link.com"> sit amet</a>', 'http://link.com'],
            ['Lorem ipsum <a id="some_id" href="http://link.com"> sit amet</a>', 'http://link.com'],
            ['Lorem ipsum <a class="some_class" href="http://link.com"> sit amet</a>', 'http://link.com'],
            ['Lorem ipsum <a class="some_class" href="//link.com"> sit amet</a>', 'http://link.com'],
            ['Lorem ipsum <a href="//link.com/?from=prov"> sit amet</a>', 'http://link.com/?from=prov'],
        ];
    }
}
