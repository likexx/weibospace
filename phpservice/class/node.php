<?php
class Node {
    // Note that timeouts less than 1000 don't work at all. :-(
    const NODE_PUSH_TIMEOUT_MS = 1500;
    const NODE_PUSH_CONNECT_TIMEOUT_MS = 1000; // Included in above timeout

    protected  $_nodeUrl = 'http://106.187.43.45:54321/nodeservice';
    
    public function __construct() {
    }
    
    public function dataPush($uid, $method, $payload) {
        $postData = array(
            'user_id' => $uid,
            'method' => $method,
            'data_payload' => $payload
        );
        $postStr = $this->_buildPostBody($postData);
        
        return $this->sendNodeRequest($postStr, $uid);
    }

    
    protected function _buildPostBody($postData) {
        $urlencoded = array();
        if (isset($postData['data_payload'])) {
             $postData['data_payload'] = json_encode($postData['data_payload']);
        }
        foreach ($postData as $k => $v) {
            $urlencoded[] = urlencode($k) . '=' . urlencode($v);
        }
        return implode('&', $urlencoded);
    }

    protected function sendNodeRequest($postStr, $uid) {
        $startTime = microtime(true);

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->_nodeUrl);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-T-Uid: ' . $uid));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postStr);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, self::NODE_PUSH_CONNECT_TIMEOUT_MS);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, self::NODE_PUSH_TIMEOUT_MS);
            $response = curl_exec($ch);

            if(curl_errno($ch)) {
                return false;
            }

            curl_close($ch);
        } catch (Exception $e) {
            return false;
        }
        
        $data = json_decode($response);
        
        if (empty($data)) {
            //throw new tag_service_exception('Empty Node response: ' . $response);
            return false;
        }
        return $data;
    }
}
// }}}
