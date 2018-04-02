<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/6
 * Time: 13:56
 */

namespace app\index\model;

use think\Loader;
use think\Model;
use RongCloud\RongCloud;

Loader::import('rongyun.rongcloud');

class RongyunBox extends Model
{


    /****************用户模块*************/
    /**
     * 获取token方法
     * @param $id   用户id
     * @param $username  用户名称
     * @param $portraitUri  用户头像
     * @return mixed
     */
    public function getToken($id, $username, $portraitUri)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->getToken($id, $username, $portraitUri);
        return $result;
    }

    /**
     * 刷新用户信息方法
     * @param $id   用户id
     * @param string $username 用户名称
     * @param string $portraitUri 用户头像
     * @return mixed
     */
    public function refresh($id, $username = '', $portraitUri = '')
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->refresh($id, $username, $portraitUri);
        return $result;
    }

    /**
     * 检查用户在线状态
     * @param $id  用户id
     * @return mixed
     */
    public function checkOnline($id)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->checkOnline($id);
        return $result;
    }

    /**
     * 封禁用户方法
     * @param $id  用户id
     * @param $minute  封禁分钟数
     * @return mixed
     */
    public function block($id, $minute)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->block($id, $minute);
        return $result;
    }


    /**
     * 解除用户封禁方法
     * @param $id  用户id
     * @return mixed
     */
    public function unBlock($id)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->unBlock($id);
        return $result;
    }

    /**
     * 获取被封禁用户方法
     * @return mixed
     */
    public function queryBlock()
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->queryBlock();
        return $result;
    }

    /**
     * 添加黑名单
     * @param $id  用户id
     * @param $bid  被加黑名单用户id
     * @return mixed
     */
    public function addBlackList($id, $bid)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->addBlackList($id, $bid);
        return $result;
    }

    /**
     * 获取某用户的黑名单列表
     * @param $id 用户id
     * @return mixed
     */
    public function queryBlackList($id)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->queryBlackList($id);
        return $result;
    }

    /**
     * 从黑名单移除用户的方法
     * @param $id 用户id
     * @param $bid 被移除的id
     * @return mixed
     */
    public function removeBlackList($id, $bid)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->User()->removeBlackList($id, $bid);
        return $result;
    }

    /*****************消息模块**********************/

    /**
     * 发送单聊消息方法
     * @param $from  发送人id  必传
     * @param $to    接受人id  必传
     * @param $obj   消息类型 RC: 必传
     * @param $content 发送消息内容  必传
     * @param $verifyBlackList 是否过滤黑名单用户 1:过滤 0:不过滤(默认)  可选
     * @param $isPersisted  客户端是否存储  1:存储(默认)  0:不存储
     * @param $isCounted   是否进行未读消息计数 0 不计数  1:计数(默认)
     * @param $isIncludeSender  发送者自身是否接受消息 0不接收(默认)  1接收
     * @param string $pushContent 自定义消息
     * @param string $pushData 针对ios
     * @param string $count 针对ios,用于控制未读消息显示数 当to只有一个时有效
     * @return mixed
     */
    public function publishPrivate($from, $to, $obj, $content, $verifyBlackList, $isPersisted, $isCounted, $isIncludeSender, $pushContent = '', $pushData = '', $count = '')
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud
            ->Message()
            ->publishPrivate($from, $to, $obj, $content, $verifyBlackList, $isPersisted, $isCounted, $isIncludeSender, $pushContent, $pushData, $count);
        return $result;
    }

    /**
     * 发送系统消息方法
     * @param $from 发送人id 必传
     * @param $to   接受人id 必传
     * @param $obj  消息类型 RC: 必传
     * @param $content  发送消息类型 必传
     * @param string $pushContent 自定义消息
     * @param string $pushData 针对 iOS 平台为 Push 通知时附加到 payload 中，Android 客户端收到推送消息  时对应字段名为 pushData。（可选）
     * @param $isPerisisted 当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行存储，0 表示为不存储、 1 表示为存储，默认为 1 存储消息。（可选）
     * @param $isCounted 当前版本有新的自定义消息，而老版本没有该自定义消息时，老版本客户端收到消息后是否进行未读消息计数，0 表示为不计数、 1 表示为计数，默认为 1 计数，未读消息数增加 1。（可选）
     * @return mixed
     */
    public function publishSystem($from, $to, $obj, $content, $pushContent = '', $pushData = '', $isPerisisted, $isCounted)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud
            ->Message()
            ->PublishSystem($from, $to, $obj, $content, $pushContent, $pushData, $isPerisisted, $isCounted);
        return $result;
    }

    /**
     * 发送广播消息
     * @param $from 发送人id 必传
     * @param $obj  发送消息类型 RC:
     * @param $content 消息内容
     * @param string $pushContent 自定义消息
     * @param string $pushData 针对 iOS 平台为 Push 通知时附加到 payload 中，Android 客户端收到推送消息时对应字段名为 pushData。(可选)
     * @param string $os 针对操作系统发送 Push，值为 iOS 表示对 iOS 手机用户发送 Push ,为 Android 时表示对 Android 手机用户发送 Push ，如对所有用户发送 Push 信息，则不需要传 os 参数。(可选)
     * @return mixed
     */
    public function broadcast($from, $obj, $content, $pushContent = '', $pushData = '', $os = '')
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud
            ->Message()
            ->broadcast($from, $obj, $content, $pushContent, $pushData, $os);
        return $result;
    }

    /**
     * 消息历史纪录下载地址获取
     * @param $date 日期 2014010102
     * @return mixed
     */
    public function getHistory($date)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud
            ->Message()
            ->getHistory($date);
        return $result;
    }

    /**
     * 消息历史记录删除方法
     * @param $date 删除指定某天某小时内的所有会话 2014010101
     * @return mixed
     */
    public function deleteMessage($date)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud
            ->Message()
            ->deleteMessage($date);
        return $result;
    }

    /**
     * 消息撤回服务
     * @param $fromUserId
     * @param $conversationType
     * @param $targetId
     * @param $messageUID
     * @param $sentTime
     * @return mixed
     */
    public function recall($fromUserId, $conversationType, $targetId, $messageUID, $sentTime)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud
            ->Message()
            ->recallMsg($fromUserId, $conversationType, $targetId, $messageUID, $sentTime);
        return $result;
    }


    /****************敏感词设置***********************/
    /**
     * 添加敏感词
     * @param $word
     * @return mixed
     */
    public function addFilter($word)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->Wordfilter()->add($word);
        return $result;
    }

    /**
     * 查询敏感词列表
     * @return mixed
     */
    public function getList()
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->Wordfilter()->getList();
        return $result;
    }

    /**
     * 移除敏感词
     * @param $word
     * @return mixed
     */
    public function deleteFilter($word)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->Wordfilter()->delete($word);
        return $result;
    }

    /**
     * 批量删除敏感词
     * @param $words 敏感词数组
     * @return mixed
     */
    public function delBatch($words)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->Wordfilter()->delBatch($words);
        return $result;
    }


    /****************会话免打扰*********************/
    /**
     * 设置会话消息免打扰
     * @param $conversationType  会话类型 双人会话2
     * @param $requestId 消息免打扰的用户id
     * @param $targetId  目标id  可以是用户id 讨论组或群的id
     * @param $isMuted  消息免打扰设置状态  0关闭 1开启
     * @return mixed
     */
    public function setNotification($conversationType, $requestId, $targetId, $isMuted)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->Conversation()->setNotification($conversationType, $requestId, $targetId, $isMuted);
        return $result;
    }

    /**
     * 查询会话消息免打扰
     * @param $conversationType  会话类型
     * @param $requestId  消息免打扰的用户id
     * @param $targetId  目标id 可以是用户id 讨论组或群的id
     * @return mixed
     */
    public function getNotification($conversationType, $requestId, $targetId)
    {
        $RongCloud = new RongCloud();
        $result = $RongCloud->Conversation()->getNotification($conversationType, $requestId, $targetId);
        return $result;
    }


    /****************在线订阅***********************/


}