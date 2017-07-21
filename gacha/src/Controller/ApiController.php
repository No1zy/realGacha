<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * real gacha Api class
 */
class ApiController extends AppController
{
    public function index(){

    }
    public function doGacha(){
        $this->autoRender = false;
        $this->response->header('Access-Control-Allow-Origin','*');

        if(empty($this->request->data())) return 'erorr';
        
        $event_id = $this->request->data('event_id'); // イベントID
        $gacha_id = $this->request->data('gacha_id'); // ガチャID
        $conditions = array(
            'event_id' => $event_id,
            'gacha_id' => $gacha_id,
        );
        $this->loadModel('Events');
        $query = $this->Events->find('all', ['conditions' => $conditions]);
        if($query->count() < 1){
            return 'error';
        }
        $result = $query->toArray()[0]; // クエリ結果
        $conditions = array(
                        'event_id' => $result->event_id,
                    );
        $this->loadModel('Images');
        $result = $this->Images->find('all', ['conditions' => $conditions]);
        $image_path = WWW_ROOT . 'iamges' . DS;

        $image_length = $result->count();
        $gacha = array();
        $gacha += array_fill(0,100 * 0.5,'N');
        $gacha += array_merge($gacha,array_fill(30,100 * 0.3,'R'));
        $gacha += array_merge($gacha,array_fill(80,100 * 0.15,'SR'));
        $gacha += array_merge($gacha,array_fill(90,100 * 0.05,'UR'));
        shuffle($gacha);

        $index = mt_rand(0,$image_length);
        $rarity = $gacha[$index];

        $conditions = array(
                        'event_id' => $event_id,
                        'rarity' => $rarity,
                    );
        $query = $this->Images->find('all', ['conditions' => $conditions]);
        $result = $query->toArray();
        $key = 0;
        if ($query->count() > 1){
            $key = array_rand($result);
        }
        $file_name = $result[$key]->file_name;
        $image_name = $result[$key]->image_name;
        $rarity = $result[$key]->rarity;

        $file = new File(WWW_ROOT . 'images' . DS . $file_name);
        // 画像フォーマット取得
        $tmp = explode('.', $file_name);
        $format = end($tmp);
        $content = base64_encode($file->read());
        
        $response = array(
            'image_name' => h($image_name), 
            'format' => $format,
            'image' => $content,
            'rarity' => $rarity,
        );
        $this->response->charset('utf-8');
        $this->response->type('json');
        $this->response->body(json_encode($response,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return $this->response;
        
    }
    
    public function saveImage(){
        $this->viewBuilder()->autolayout(false);
        $event_id = $this->request->query('id');
        $this->set('event_id',$event_id);
        //POSTデータが無い場合
        if(empty($this->request->data)) return;
        $image_names = $this->request->data('image_names');
        $request_rare = $this->request->data('rarities');
        $rarities = array(); // 各カードのレアリティ
        //リクエストからレアリティを割り当てる
        foreach($request_rare as $rarity_number){
            switch($rarity_number){
                case 1:
                    $rarity = "N";
                    break;
                case 2:
                    $rarity = "R";
                    break;
                case 3:
                    $rarity = "SR";
                    break;
                case 4:
                    $rarity = "UR";
                    break;
            }
            $rarities[] = $rarity;
        }
        $event_id = $this->request->data('event_id');
        //画像の名前が無い場合
        if($event_id == null && empty($rarities) && empty($image_names)) return;
    
        $accepted_files = array(); // 許可されたファイル
        //ファイルのバリデーションチェック
        foreach($this->request->data("images") as $data){
            if(!is_array($data)){ 
                return false;
            }
            setlocale(LC_ALL, 'ja_JP.UTF-8');
            $file_name = basename($data["name"]);
            if ($data['size'] > 1000000) {
                throw new RuntimeException('ファイルサイズが大きすぎます');
                return;
            }
            $pattern = "/^[^.]+\.png$||^[^.]+\.jpg$||^[^.]+\.jpeg$/";
            if(!preg_match($pattern, $file_name)){
                throw new RuntimeException('許可されていない拡張子です');
                return;
            }
                $accepted_files[] = $data;
                move_uploaded_file($data["tmp_name"], WWW_ROOT . "images/" . $file_name );
        }
        //格納するデータの生成
        setlocale(LC_ALL, 'ja_JP.UTF-8');
        for ($i = 0; $i < sizeof($accepted_files); ++$i){
            $data[$i]["image_name"] = $image_names[$i];                      // ガチャのカード名
            $data[$i]["file_name"] = basename($accepted_files[$i]['name']); // ファイル名
            $data[$i]["rarity"] = $rarities[$i];
            $data[$i]['event_id'] = $event_id;
        }
        //データの格納
        $this->loadModel('Images');
        $entities = $this->Images->newEntities($data);
        foreach($entities as $entity){
            $this->Images->save($entity,['checkRules' => false]);
        }
    }
    /**
     * ガチャIDを発行
     *
     * @return int gacha ID
     */
    public function createId(){
        $this->response->header('Access-Control-Allow-Origin','*');

        $this->autoRender = false;
        if(empty($this->request->data['id'])) return;
        
        $gacha_id = $this->request->data['id'];
        $this->loadModel('Gachas');
        $conditions = array('gacha_id' => $gacha_id);
        $query = $this->Gachas->find('all',['conditions' => $conditions]);
        if ($query->count() < 1) return;
        $this->response->charset('UTF-8');
        $this->response->type('json');

        $event_id = array();
        $event_id["event_id"] = $this->_makeRandStr();
        $data = array(
            'event_id' => $event_id["event_id"],
            'gacha_id' => $gacha_id,
        );
        $this->loadModel('Events');
        $entity = $this->Events->newEntity($data);
        $this->Events->save($entity,['checkRules' => false]);

        echo json_encode($event_id,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
    * ランダム文字列生成 (英数字)
    * @return string random strings
    */
    private function _makeRandStr() {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $r_str = null;
        for ($i = 0; $i < 16; $i++) {
            $r_str .= $str[mt_rand(0, count($str) - 1)];
        }
        return $r_str;
    }
}