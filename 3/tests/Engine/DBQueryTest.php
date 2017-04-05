<?php

namespace Tests\Engine;

use PHPUnit\Framework\TestCase as BaseTestCase;
use App\Engine\DB;

/**
 * Class DBQueryTest.
 */
class DBQueryTest extends BaseTestCase
{
    /**
     * @var DB
     */
    private $db;

    protected function setUp()
    {
        $this->db = new DB(getenv('DB_DATABASE'));
        $this->db->exec('CREATE TABLE IF NOT EXISTS `foo` (`id` INTEGER PRIMARY KEY AUTOINCREMENT, `bar` INTEGER DEFAULT 0)');
    }

    public function testInsertData()
    {
        $sql = 'INSERT INTO `foo` (`bar`) VALUES (10)';

        $result = $this->db->query($sql);

        $this->assertTrue($result);

        $last_id = $this->db->getLastId();

        $this->assertEquals(1, $last_id);
    }

    public function testUpdateData()
    {
        $sql = 'UPDATE `foo` SET `bar` = 20 WHERE `id`= 1';

        $result = $this->db->query($sql);

        $this->assertTrue($result);

        $aff = $this->db->countAffected();

        $this->assertEquals(1, $aff);
    }

    public function testSelectData()
    {
        $sql = 'SELECT * FROM `foo`';

        $result = $this->db->query($sql);

        $this->assertEquals(1, $result->num_rows);
        $this->assertEquals(1, count($result->rows));
        $this->assertEquals(1, $result->rows[0]['id']);
        $this->assertEquals(20, $result->rows[0]['bar']);
        $this->assertEquals(2, count($result->row));
        $this->assertEquals(20, $result->row['bar']);
    }

    public function testDeleteData()
    {
        $sql = 'DELETE FROM `foo` WHERE `bar` = 20';

        $result = $this->db->query($sql);

        $this->assertTrue($result);

        //final db test - clear all data
        $this->db->exec('DROP TABLE `foo`');
    }

    protected function tearDown()
    {
        $this->db = null;
    }
}
