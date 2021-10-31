<?php
/*
 * @Theme Name:One Nav
 * @Theme URI:https://www.iotheme.cn/
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-06-03 08:55:58
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 13:43:31
 * @FilePath: \onenav\inc\email-notify.php
 * @Description: 
 */

require get_theme_file_path('/inc/mailfunc/mailfunc.php');

//评论通过 通知评论者
add_action('comment_unapproved_to_approved', 'io_comment_approved');
function io_comment_approved($comment) {
    if(is_email($comment->comment_author_email)) {
        $post_link = get_permalink($comment->comment_post_ID);
        // 邮件标题，可自行更改
        $title =  sprintf( __('您在 [%s] 的评论已通过审核', 'i_theme'), get_option('blogname') );
        $comment_author_email = trim($comment->comment_author_email);
        $post = get_post($comment->comment_post_ID);
        $args = array(
            'parentAuthor' => $comment->comment_author,
            'parentCommentDate' => $comment->comment_date,
            'parentCommentContent' => $comment->comment_content,
            'postTitle' => $post->post_title,
            'commentLink' => get_comment_link( $comment->comment_ID )
        );
        if(filter_var( $comment_author_email, FILTER_VALIDATE_EMAIL)){
            io_async_mail('', $comment_author_email, $title , $args, 'comment-pass');
        }     
    }
}

//用户账户被删除通知用户
function iwilling_delete_user( $user_id ) {
    global $wpdb;
    $site_name = get_bloginfo('name');
    $user_obj = get_userdata( $user_id );
    $email = $user_obj->user_email;
    $subject = "帐号删除提示：".$site_name."";
    $message = EMAIL_HEADER_TOP.'您在' .$site_name. '的账户已被管理员删除！'.EMAIL_HEADER_BOT.'<p style="color: #6e6e6e;font-size:13px;line-height:24px;">如果您对本次操作有什么异议，请联系管理员反馈！<br/>我们会在第一时间处理您反馈的问题.</p>'.EMAIL_FOOTER;
    wp_mail( $email, $subject, $message, "Content-Type: text/html; charset=UTF-8");
}
//add_action( 'delete_user', 'iwilling_delete_user' );


/**
 * 投稿文章邮件通知审核.
 * @param $post
 */
function io_pending_to_publish($post)
{
    //发送邮件
    $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
    if (filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
        $subject = sprintf(__('[%s]上有新的待审投稿', 'i_theme'), get_bloginfo('name'));
        $args = array(
            'postTitle' => $post,
        );
        io_async_mail('', $admin_email, $subject, $args, 'contribute-post');
    }
}
//add_action('pending_to_publish', 'ct_pending_to_publish', 10, 1);
add_action('io_contribute_to_publish', 'io_pending_to_publish', 10, 1);