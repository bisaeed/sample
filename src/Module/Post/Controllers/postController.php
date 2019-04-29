<?php
/**
 * Created by PhpStorm.
 * User: booji
 * Date: 29/04/19
 * Time: 10:59
 */

namespace Module\Post\Controllers;

use \Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;
use Lib\Database\DB;
use Lib\Validation\validation;
use Lib\Auth\Auth;

class postController
{

    public $db;
    public $key;
    public $secretKey;

    public function __construct(DB $db,$key = null, $secretKey = null)
    {
        $this->db = $db;
        $this->key = $key;
        $this->secretKey = $secretKey;
    }

    public function create(Request $request)
    {

        $token = $request->headers->get('auth-token');

        // check user login or not
        if (!Auth::isLogin($token, $this->key)) {

            // log
            error_log('postController::create - first login then try Please - ' . date('Y-m-d H:i:s'), 3, '/var/www/blog/log.txt');

            $response = [
                'status' => false,
                'message' => 'first login then try Please'
            ];
        }

        // get post information
        $post = (object) $request->request->all();

        // ------------------- validation ------------------
        $validate = validation::run($post,[
            'title' => 'notEmpty',
            'text' => 'notEmpty',
        ]);

        if(!empty($validate)) {

            echo json_encode([
                'status ' => 'validation error',
                'message' => $validate
            ]);die;
        }
        // -------------------- validation -----------------

        // insert post in db
        $this->db->insert('title, text, user_id','posts', ':title, :text, :user_id')
            ->getQuery()
            ->params(':title', $post->title)
            ->params(':text', $post->text)
            ->params(':user_id', Auth::userId())
            ->execution();

        $response = [
            'status' => true,
            'message' => 'post inserted successfully'
        ];


        echo json_encode($response);die;

    }

    public function edit(Request $request, $id) {

        $token = $request->headers->get('auth-token');

        // check user login or not
        if (!Auth::isLogin($token, $this->key)) {

            // log
            error_log('postController::edit - first login then try Please - ' . date('Y-m-d H:i:s'), 3, '/var/www/blog/log.txt');

            $response = [
                'status' => false,
                'message' => 'first login then try Please'
            ];
        }

        // get post information
        $post = (object)$request->request->all();

        $result = $this->db->update('posts', 'title=:title, text=:text, updated=:updated')
            ->where('id = :id AND user_id = :user_id')
            ->getQuery()
            ->params(':title', $post->title)
            ->params(':text', $post->text)
            ->params(':updated', date('Y:m:d H:i:s'))
            ->params(':id', $id)
            ->params(':user_id', Auth::userId())
            ->execution();

        // check update successfully or failed
        if($result->rowUpdatedCount()) {

            $response = [
                'status' => true,
                'message' => 'post updated successfully'
            ];
        }
        else {

            $response = [
                'status' => false,
                'message' => 'post update failed.'
            ];
        }

        echo json_encode($response);die;

    }

    public function delete(Request $request, $id) {

        $token = $request->headers->get('auth-token');

        // check user login or not
        if (!Auth::isLogin($token, $this->key)) {

            // log
            error_log('postController::delete - first login then try Please - ' . date('Y-m-d H:i:s'), 3, '/var/www/blog/log.txt');

            $response = [
                'status' => false,
                'message' => 'first login then try Please'
            ];
        }

        $this->db->delete('posts')
            ->where('id = :id')
            ->getQuery()
            ->params(':id', $id)
            ->execution();

        $response = [
            'status' => true,
            'message' => 'post deleted successfully'
        ];

        echo json_encode($response);die;


    }

    public function addComment(Request $request, $id) {

        $token = $request->headers->get('auth-token');

        // check user login or not
        if (!Auth::isLogin($token, $this->key)) {

            // log
            error_log('postController::delete - first login then try Please - ' . date('Y-m-d H:i:s'), 3, '/var/www/blog/log.txt');

            $response = [
                'status' => false,
                'message' => 'first login then try Please'
            ];
        }

        // get post information
        $post = (object) $request->request->all();

        // insert post in db
        $this->db->insert('text, user_id, post_id','comments', ':text, :user_id, :post_id')
            ->getQuery()
            ->params(':text', $post->text)
            ->params(':user_id', Auth::userId())
            ->params(':post_id', $id)
            ->execution();

        $response = [
            'status' => true,
            'message' => 'comment inserted successfully'
        ];

        echo json_encode($response);die;

    }

    public function deleteComment() {

        //
    }

}