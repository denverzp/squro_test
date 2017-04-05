<?php

namespace Tests\Engine;

use PHPUnit\Framework\TestCase as BaseTestCase;
use App\Engine\DB;

/**
 * Class DBConnectTest.
 */
class DBConnectTest extends BaseTestCase
{
    /**
     * @var DB
     */
    protected function setUp()
    {
        if(false === array_key_exists('HTTP_HOST', $_SERVER)){
            $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
        }

        require_once realpath(__DIR__ . '/../../bootstrap/config.php');
    }

    /**
     * @expectedException \ErrorException
     * @dataProvider addWrongInitData
     *
     * @param $data
     */
    public function testWrongInit($database)
    {
        $db = new DB($database);
    }

    /**
     * @return array
     */
    public function addWrongInitData()
    {
        return [
            'null' => [null],
            'empty' => [''],
        ];
    }

    /**
     * @dataProvider addGoodInitData
     *
     * @param $data
     */
    public function testSuccessInit($database)
    {
        $db = new DB($database);

        $this->assertInternalType('object', $db);
        $this->assertObjectHasAttribute('link', $db);
        $this->assertObjectHasAttribute('log', $db);
        $this->assertAttributeInternalType('object', 'link', $db);
        $this->assertAttributeInternalType('object', 'log', $db);
    }

    /**
     * @return array
     */
    public function addGoodInitData()
    {
        return [
            'good' => [getenv('DB_DATABASE')],
        ];
    }
}
