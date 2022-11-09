<?php
/**
 * Copyright (c) Meta Platforms, Inc. and affiliates. All Rights Reserved
 */

namespace Facebook\BusinessExtension\Controller\Adminhtml\Ajax;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Facebook\BusinessExtension\Model\System\Config as SystemConfig;

class Fbtoken extends AbstractAjax
{
    public function executeForJson()
    {
        $oldAccessToken = $this->systemConfig->getAccessToken();
        $response = [
            'success' => false,
            'accessToken' => $oldAccessToken
        ];
        $accessToken = $this->getRequest()->getParam('accessToken');
        if ($accessToken) {
            $this->systemConfig->saveConfig(SystemConfig::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_ACCESS_TOKEN, $accessToken);
            $response['success'] = true;
            $response['accessToken'] = $accessToken;
            if ($oldAccessToken != $accessToken) {
                $this->_fbeHelper->log("Updated Access token...");
                $datetime = $this->_fbeHelper->createObject(DateTime::class);
                $this->systemConfig->saveConfig(
                    SystemConfig::XML_PATH_FACEBOOK_BUSINESS_EXTENSION_ACCESS_TOKEN_CREATION_TIME,
                    $datetime->gmtDate('Y-m-d H:i:s')
                );
            }
        }
        return $response;
    }
}
