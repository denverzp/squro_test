<?php

namespace Tests\Engine;

use PHPUnit\Framework\TestCase as BaseTestCase;
use App\Engine\DB;

/**
 * Class DBCreateTest.
 */
class DBCreateTest extends BaseTestCase
{
    /**
     * @var DB
     */
    private $db;

    protected function setUp()
    {
        $this->db = new DB(getenv('DB_DATABASE'));
    }

    public function testCreateTable()
    {
        //create table
        $sql = 'CREATE TABLE IF NOT EXISTS `foo` (`bar` INT DEFAULT 0)';

        $result = $this->db->exec($sql);

        $this->assertTrue($result);

        //check existed table
        $sql = 'SELECT count(*) FROM `sqlite_master` WHERE `type`="table" AND `name`="foo"';

        $result = $this->db->query($sql);

        $this->assertEquals(1, $result->num_rows);
    }

    public function testDeleteTable()
    {
        $result = $this->db->exec('DROP TABLE `foo`');

        $this->assertTrue($result);
    }

    protected function tearDown()
    {
        $this->db = null;
    }
}
