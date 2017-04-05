<?php

namespace App\Engine;

use SQLite3;

/**
 * Class DB.
 */
class DB
{
    /**
     * @var SQLite3
     */
    private $link;
    private $log;

    /**
     * @param $database
     */
    public function __construct($database)
    {
        $this->log = new Log('db.log');

        try {
            $this->link = new SQLite3(DIR_DATABASE . $database);
        } catch (\Exception $e){
            $this->log->write('Error: Could not open database ' . $e->getMessage());

            throw new \ErrorException('Could not open database...');
        }
    }

    /**
     * @param $sql
     *
     * @return mixed
     */
    public function query($sql)
    {
        $query = $this->link->query($sql);

        if ( ! $this->link->lastErrorCode()) {
            if (0 !== $query->numColumns()) {
                $data = [];

                while ($row = $query->fetchArray(SQLITE3_ASSOC)) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->num_rows = count($data);
                $result->row = isset($data[0]) ? $data[0] : [];
                $result->rows = $data;

                unset($data);

                $query->finalize();

                return $result;
            } else {
                return true;
            }
        } else {
            $this->log->write('Error: ' . $this->link->lastErrorMsg() . "\n" . 'Error No: ' . $this->link->lastErrorCode() . "\n" . $sql);

            return false;
        }
    }

    public function exec($sql)
    {
        return $this->link->exec($sql);
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function escape($value)
    {
        return $this->link->escapeString($value);
    }

    /**
     * @return mixed
     */
    public function countAffected()
    {
        return $this->link->changes();
    }

    /**
     * @return mixed
     */
    public function getLastId()
    {
        return $this->link->lastInsertRowID();
    }

    public function __destruct()
    {
        $this->link->close();
    }
}
