<?php
use Psr\Http\Message\ResponseInterface as Response;

class ApiController
{
    private $dbHelper;

    public function __construct()
    {
        $dbConf = require ROOT . '/config/local_db_config.php';
        $this->dbHelper = new DBHelper($dbConf);
    }

    /**
     * @param $response Response
     * @return Response
     */
    public function getUsersList($response)
    {
        $list = $this->dbHelper->getUserList();
        $payload = json_encode([
            'status' => 'success',
            'data' => $list
        ]);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    /**
     * @param $response Response
     * @param $id integer
     * @return Response
     */
    public function getUserById($response, $id)
    {
        if(!$id){
            $result = [
                'status' => 'error',
                'msg' => 'Missed required parameter id'
            ];
        } else {
            $user = $this->dbHelper->getUserById($id);
            if($user){
                $result = [
                    'status' => 'success',
                    'data' => $user
                ];
            } else {
                $result = [
                    'status' => 'error',
                    'msg' => 'There is not user with this id'
                ];
            }
        }
        $payload = json_encode($result);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    /**
     * @param $response Response
     * @param $id integer
     * @return Response
     */
    public function removeUser($response, $id)
    {
        if(!$id){
            $result = [
                'status' => 'error',
                'msg' => 'Missed required parameter id'
            ];
        } else {
            $resultOfRemoving = $this->dbHelper->removeUser($id);
            if($resultOfRemoving){
                $result = [
                    'status' => 'success',
                    'data' => 'User with id '.$id.' removed successfully'
                ];
            } else {
                $result = [
                    'status' => 'error',
                    'msg' => 'There is not user with this id'
                ];
            }
        }
        $payload = json_encode($result);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    /**
     * @param $response Response
     * @param $data array
     * @return Response
     */
    public function createUser($response, $data)
    {
        $data = $this->prepareDataForModel($data);
        $model = new UserModel($data, $this->dbHelper);
        $resultOfSaving = $model->save();

        $status = $resultOfSaving['status'] ? 'success' : 'error';
        $result = [
            'status' => $status,
            'msg' => $resultOfSaving['msg']
        ];
        $payload = json_encode($result);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param $response Response
     * @param $data array
     * @return Response
     */
    public function updateUser($response, $data)
    {
        if(!isset($data['id'])){
            $result = [
                'status' => 'error',
                'msg' => 'Parameter id is required'
            ];
        } else {
            $oldData = $this->dbHelper->getUserById($data['id'], true);
            if(!$oldData){
                $result = [
                    'status' => 'error',
                    'msg' => 'There is not user with this id'
                ];
            } else {
                $data = $this->prepareDataForModel($data, true);
                if(isset($data['password'])) {
                    unset($oldData['hash']);
                }
                foreach ($data as $key => $value){
                    $oldData[$key] = $value;
                }
                $model = new UserModel($oldData, $this->dbHelper);
                $resultOfSaving = $model->save();

                $status = $resultOfSaving['status'] ? 'success' : 'error';
                $result = [
                    'status' => $status,
                    'msg' => $resultOfSaving['msg']
                ];
            }
        }

        $payload = json_encode($result);
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    private function prepareDataForModel($data, $forUpdate = false)
    {
        $result = [];

        if($forUpdate && isset($data['id'])) $result['id'] = $data['id'];
        if(isset($data['username'])) $result['username'] = $data['username'];
        if(isset($data['password'])) $result['password'] = $data['password'];
        if(isset($data['name'])) $result['name'] = $data['name'];
        if(isset($data['email'])) $result['email'] = $data['email'];

        return $result;
    }
}