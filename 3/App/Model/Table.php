<?php

namespace App\Model;

use App\Engine\Model;

class Table extends Model
{
    /**
     * Check existing table.
     */
    public function checkExistTables()
    {
        // each item in array - MUST BE responding method for create table
        // like: [0 => some_name] - $this->createTableSomename
        $tables_list = [
            'nodes',
        ];

        foreach ($tables_list as $table) {
            if( ! $this->isExistTable($table)){
                $this->createTable($table);
            }
        }
    }

    /**
     * check exists one table.
     *
     * @param string $name
     *
     * @return bool
     */
    private function isExistTable($name)
    {
        $sql = 'SELECT count(*) as count FROM `sqlite_master` WHERE `type`="table" AND `name`="' . $this->db->escape($name) . '"';

        $result = $this->db->query($sql);

        return (int) $result->row['count'];
    }

    /**
     * Common create table metod.
     *
     * @param string $name
     *
     * @throws Exception
     */
    private function createTable($name)
    {
        $create_method = 'createTable' . ucfirst(str_replace(['_'], '', $name));

        if(true === method_exists($this, $create_method)){
            $this->{$create_method}();
        } else {
            throw new Exception('Not isset create method for table ' . $name);
        }
    }

    /**
     *  Create table `nodes`.
     */
    protected function createTableNodes()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `nodes` ('
            . ' `id` INTEGER PRIMARY KEY AUTOINCREMENT,'
            . ' `parent_id` INTEGER DEFAULT 0,'
            . ' `name` TEXT DEFAULT NULL,'
            . ' `order` INTEGER DEFAULT NULL'
            . ')';

        $this->db->exec($sql);
    }
}
