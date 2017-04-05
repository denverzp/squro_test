<?php

namespace App\Controller;

use App\Engine\Controller;
use App\Engine\Registry;
use App\Model\Tree;

/**
 * Class TreesController.
 */
class TreesController extends Controller
{
    /**
     * @var Tree
     */
    private $model;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);

        $this->model = new Tree($registry);
    }

    /**
     * get all tree nodes.
     */
    public function index()
    {
        $json = [
            'status' => 'success',
            'nodes' => $this->model->getAllNodes(),
        ];

        $this->jsonAnswer($json);
    }

    /**
     * Add new node.
     */
    public function store()
    {
        $json = $this->validateStore();

        if($json['status']){
            $node_id = $this->model->store($this->request->post['value'], $this->request->post['parent'], $this->request->post['order']);

            if($node_id){
                $json = [
                    'status' => true,
                    'message' => $node_id,
                ];
            } else {
                $json = [
                    'status' => false,
                    'message' => 'cannot add node',
                ];
            }
        }

        $this->jsonAnswer($json);
    }

    /**
     * Change node data.
     */
    public function update()
    {
        $json = $this->validateUpdate();

        if($json['status']){
            $json = [
                'status' => $this->model->update($this->request->post['type'], $this->request->post['id'], $this->request->post['value']),
                'mesage' => 'node data update',
            ];
        }

        $this->jsonAnswer($json);
    }

    /**
     * update items order.
     */
    public function order()
    {
        if(true === array_key_exists('value', $this->request->post) && true === is_array($this->request->post['value'])){
            foreach ($this->request->post['value'] as $id => $order) {
                $this->model->update('order', $id, $order);
            }
        }
    }

    /**
     * Delete node.
     */
    public function destroy()
    {
        $json['status'] = false;

            if(true === array_key_exists('id', $this->request->post) && ! empty($this->request->post['id'])){
                $json = [
                    'status' => $this->model->destroy($this->request->post['id']),
                    'mesage' => 'node destroy',
                ];
            } else {
                $json['message'] = 'not send id';
            }

        $this->jsonAnswer($json);
    }

    /**
     * Correct json answer.
     *
     * @param array $json
     */
    private function jsonAnswer(array $json)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($json);
    }

    /**
     * Validate update node data.
     *
     * @return string
     */
    private function validateUpdate()
    {
        $result = [
            'status' => true,
        ];

        if(false === array_key_exists('type', $this->request->post) || empty($this->request->post['type'])){
            $result = [
                'status' => false,
                'message' => 'not send type.',
            ];
        } else {
            $allowed_types = [
                'parent_id',
                'name',
                'order',
            ];

            if(false === in_array($this->request->post['type'], $allowed_types, false)){
                $result = [
                    'status' => false,
                    'message' => 'this type is not allowed.',
                ];
            }
        }

        if(false === array_key_exists('id', $this->request->post) || empty($this->request->post['id'])){
            $result = [
                'status' => false,
                'message' => 'not send id',
            ];
        }

        if(false === array_key_exists('value', $this->request->post)){
            $result = [
                'status' => false,
                'message' => 'not send value',
            ];
        }

        return $result;
    }

    /**
     * Validate update node data.
     *
     * @return string
     */
    private function validateStore()
    {
        $result = [
            'status' => true,
        ];

        if(false === array_key_exists('value', $this->request->post) || empty($this->request->post['value'])){
            $result = [
                'status' => false,
                'message' => 'not send node name',
            ];
        }

        if(false === array_key_exists('parent', $this->request->post) || null === $this->request->post['parent']){
            $result = [
                'status' => false,
                'message' => 'not send node parent id',
            ];
        }

        if(false === array_key_exists('order', $this->request->post) || null === $this->request->post['order']){
            $result = [
                'status' => false,
                'message' => 'not send node order',
            ];
        }

        return $result;
    }
}
