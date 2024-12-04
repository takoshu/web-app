<?php 
namespace controller\topic\delete;
use lib\Msg;
use db\TopicQuery;
use model\TopicModel;
use model\UserModel;
use lib\Auth;
use Throwable;

function post()
{

    Auth::requireLogin();

    $topic = new TopicModel;
    $topic->id = get_param('topic_id', null);

    $user = UserModel::getSession();
    Auth::requirePermission($topic->id, $user);

    try
    {
    $is_success = TopicQuery::delete($topic);
    } 
    catch(Throwable $e) 
    {
        Msg::push(Msg::DEBUG, $e->getMessage());
        $is_success = false;
    }

    if($is_success) 
    {
        Msg::push(Msg::INFO, 'トピックの削除に成功しました。');
        redirect('topic/archive');

    }
    else 
    {
        Msg::push(Msg::ERROR, 'トピックの削除に失敗しました。');
        TopicModel::setSession($topic);
        redirect(GO_REFERER);

    }
}

?>