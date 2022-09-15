<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;
use SebastianBergmann\GlobalState\Restorer;

class Api extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function users_get(){
        $user = $this->db->get('tbl_user')->result();


        if($user){
            $data_json = array(
                "success" => true,
                "message" => "data found",
                "data" => $user
            );
        }else{
            $data_json = array(
                "success" => false,
                "message" => "data not found",
                "data" => null
            );
        }

        $this->response($data_json, RestController::HTTP_OK);
    }

    public function users_post(){
       
        $data = json_decode($this->input->raw_input_stream);

        $data_insert = [
            "nama_user" => $data->nama,
            "email" => $data->email,
            "password" => md5($data->password),
            "role" => $data->role
        ];

        $insert = $this->db->insert('tbl_user',$data_insert);

        if($insert){
            $data_json = array(
                "success" => true,
                "message" => "user add success",
                "data" => [
                    $data_insert["nama_user"],
                    $data_insert["email"]
                ]
            );
        }else{
            $data_json = array(
                "success" => false,
                "message" => "user add fail",
                "data" => [
                    $data_insert["nama_user"],
                    $data_insert["email"]
                ]
            );
        }

        $this->response($data_json, RestController::HTTP_OK);
    }

    public function users_put($id){
        $data =  json_decode($this->input->raw_input_stream);
        
        if($data->password == ''){
            $data_update = [
                "nama_user" => $data->nama,
                "email" => $data->email,
                "role" => $data->role
            ];
        }else{
            $data_update = [
                "nama_user" => $data->nama,
                "email" => $data->email,
                "password" => md5($data->password),
                "role" => $data->role
            ];
        }

        $this->db->where('id', $id);
        $this->db->set($data_update);
        $update = $this->db->update('tbl_user');

        if($update){
            $data_json = array(
                "success" => true,
                "message" => "user update success",
                "data" => $data_update
            );
        }else{
            $data_json = array(
                "success" => false,
                "message" => "user update false",
                "data" => $data_update
            );
        }

        $this->response($data_json, RestController::HTTP_OK);
    }

    public function users_delete($id){

        $this->db->where('id', $id);
        $delete = $this->db->delete('tbl_user');
       
        if($delete){
            $data_json = array(
                "success" => true,
                "message" => "delete user success"
            );
        }else{
            $data_json = array(
                "success" => false,
                "message" => "delete user fail"
            );
        }

        $this->response($data_json, RestController::HTTP_OK);
    }

    public function login_post(){
        $data =  json_decode($this->input->raw_input_stream);
        
        $email = $data->email;
        $pass = $data->password;

        $login = $this->db->get_where('tbl_user', array('email' =>$email, 'password' => md5($pass)))->row_object();

        if($login){
            $data_json = array(
                "success" => true,
                "message" => "Login true",
                "data" => $login
            );
        }else{
            $data_json = array(
                "success" => false,
                "message" => "Login false",
                "data" => null
            );
        }

        $this->response($data_json, RestController::HTTP_OK);
    }
}
