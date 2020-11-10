<?php
class woofiltersModel extends modelWpf {
	public function __construct() {
		$this->_setTbl('filters');
	}
	public function save($data = array()){
		$id = isset($data['id']) ? ($data['id']) : false;
		$title = isset($data['title']) ? ($data['title']) : false;
        //already created filter
        if(!empty($id) && !empty($title)) {
            $data['id'] = (string)$id;
            $statusUpdate = $this->updateById( $data , $id );
            if($statusUpdate){
                return $id;
            }
        } else if( empty($id) && !empty($title)){  //empty filter
            $idInsert = $this->insert( $data );
            if($idInsert){
                if(empty($data['title'])){
                    $data['title'] = (string)$idInsert;
                }
				$data['id'] = (string)$idInsert;
                $this->updateById( $data , $idInsert );
            }
            return $idInsert;
        } else //empty title
            $this->pushError (__('Title can\'t be empty or more than 255 characters', WPF_LANG_CODE), 'title');
        return false;
    }
	protected function _dataSave($data, $update = false){
        $settings = isset($data['settings']) ? $data['settings'] : array();
		$data['settings']['css_editor'] = isset($settings['css_editor']) ? base64_encode($settings['css_editor']) : '';
		$data['settings']['js_editor'] = isset($settings['js_editor']) ? base64_encode($settings['js_editor']) : '';
		$data['settings']['filters']['order'] = isset($settings['filters']) && isset($settings['filters']['order']) ? stripslashes($settings['filters']['order']) : '';
		$settingData = array('settings' => $data['settings']);
		$data['setting_data'] = serialize($settingData);
		return $data;
	}
}



