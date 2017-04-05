<?php

namespace App\Model;

use App\Engine\Model;

class Tree extends Model
{
    /**
     * @return array
     */
    public function getAllNodes()
    {
        $result = [];

        $sql = 'SELECT * FROM `nodes` ORDER BY `parent_id`, `order`';

        $query = $this->db->query($sql);

        if($query->num_rows){
            $result = $this->getTreeViewNodes($query->rows);
        }

        return $result;
    }

    /**
     * sort nodes for tree-view.
     *
     * @param array $nodes
     */
    protected function getTreeViewNodes(array $nodes)
    {
        $result = [];

        //sort by parent
        foreach ($nodes as $node) {
            $result[$node['parent_id']][] = $node;
        }

        //final sort
        return $this->getNodesChildren(0, $result);
    }

    /**
     * Recursive set tree for nodes.
     *
     * @param $parent_id
     * @param array $nodes
     *
     * @return array
     */
    protected function getNodesChildren($parent_id, array $nodes)
    {
        $result = [];

        if(true === array_key_exists($parent_id, $nodes)){
            $key = 0;

            foreach ($nodes[$parent_id] as $node) {
                $result[$key] = $node;

                if(true === array_key_exists($node['id'], $nodes)){
                    $childrens = $this->getNodesChildren($node['id'], $nodes);

                    if(0 !== count($childrens)){
                        $result[$key]['childrens'] = $childrens;
                    }
                }

                ++$key;
            }
        }

        return $result;
    }

    /**
     * Add new node.
     *
     * @param type $name
     * @param type $parent_id
     * @param type $order
     *
     * @return type
     */
    public function store($name, $parent_id, $order) {
        $sql = 'INSERT INTO `nodes` (`name`, `parent_id`, `order`) VALUES ('
            . '"' . $this->db->escape($name) . '",'
            . '"' . (int) $parent_id . '",'
            . '"' . (int) $order . '"'
            . ')';

        $result = $this->db->query($sql);

        if($result){
            $result = $this->db->getLastId();
        }

        return $result;
    }

    /**
     * Update node data.
     *
     * @param type $type
     * @param type $id
     * @param type $value
     *
     * @return string|bool
     */
    public function update($type, $id, $value)
    {
        $sql = 'UPDATE `nodes` SET `' . $type . '` = "' . $this->db->escape($value) . '" WHERE `id` = "' . (int) $id . '"';

        return $this->db->query($sql);
    }

    /**
     * Delete node.
     *
     * @param type $id
     *
     * @return type
     */
    public function destroy($id)
    {
        $sql = 'DELETE FROM `nodes` WHERE `id` = "' . (int) $id . '"';

        return $this->db->query($sql);
    }
}
