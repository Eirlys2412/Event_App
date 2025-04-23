<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Modules\Comments\Models\Comment;
use Illuminate\Support\Facades\DB;

class JoinRequestNotification extends Notification
{
    use Queueable;

    protected $joinRequest;

    public function __construct($joinRequest)
    {
        $this->joinRequest = $joinRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // Hoặc thêm 'mail' nếu muốn gửi email
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Người dùng {$this->joinRequest->user->name} muốn tham gia nhóm {$this->joinRequest->group->name}. Bạn có muốn duyệt không?",
            'group_id' => $this->joinRequest->group_id,
            'request_id' => $this->joinRequest->id,
        ];
    }

    // Nếu muốn gửi email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line("Người dùng {$this->joinRequest->user->name} muốn tham gia nhóm {$this->joinRequest->group->name}.")
            ->action('Xem yêu cầu', url('/groups/' . $this->joinRequest->group_id))
            ->line('Cảm ơn bạn đã sử dụng ứng dụng!');
    }
}

class NewCommentNotification extends Notification
{
    use Queueable;
    private $comment;
    public function __construct(Comment $c){ $this->comment = $c; }
    public function via($notifiable){ return ['database']; }
    public function toArray($notifiable){
        return [
            'actor_id'=> $this->comment->user_id,
            'item_code'=> $this->comment->item_code,
            'item_id'=> $this->comment->item_id,
            'message'=> 'Có bình luận mới trên bài viết của bạn',
        ];
    }
}

class ReactionNotification extends Notification
{
    use Queueable;

    protected $reaction;

    public function __construct($reaction)
    {
        $this->reaction = $reaction;
    }

    public function via($notifiable)
    {
        return ['database']; // Hoặc thêm 'mail' nếu muốn gửi email
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Có một phản hồi mới trên bài viết của bạn",
            'reaction_id' => $this->reaction->id,
        ];
    }

    // Nếu muốn gửi email
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line("Có một phản hồi mới trên bài viết của bạn")
            ->line('Cảm ơn bạn đã sử dụng ứng dụng!');
    }
}