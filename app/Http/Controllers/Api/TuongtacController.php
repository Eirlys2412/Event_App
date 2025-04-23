<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Tag;
use App\Models\User;
use App\Modules\Tuongtac\Models\TPage;
use App\Modules\Blog\Models\Blog as TBlog;
use App\Modules\Tuongtac\Models\TPageItem;
use App\Modules\Resource\Models\Resource;
use Illuminate\Support\Facades\Validator;
use App\Modules\Comments\Models\Comment as TComment;
use App\Models\TNotice;
use  App\Modules\Tuongtac\Models\TUserpage;
use  App\Modules\Tuongtac\Models\TTag;
use  App\Modules\Tuongtac\Models\TTagItem;
use  App\Modules\Tuongtac\Models\TMotion;
use  App\Modules\Tuongtac\Models\TMotionItem;
use  App\Modules\Tuongtac\Models\TRecommend;
use  App\Modules\Tuongtac\Models\TVoteItem;
use App\Modules\Events\Models\Event;
use App\Models\Like;
use App\Models\Bookmark;
use App\Models\Vote;
 
class TuongtacController extends Controller
{
    public function saveCommentArr($data)
    {
        $comment = TComment::create($data);
        if($data['item_code'] == 'tblog')
        {
            $notice = array();
            $blog = TBlog::findOrFail($data['item_id']);
            $notice['user_id'] = $blog->user_id;
            $notice['item_id'] =  $data['item_id'] ;
            $notice['item_code'] =  $data['item_code'] ;
            $user = User::find($data['user_id']);
            $notice['title'] =  $user->full_name .' thêm bình luận bài viết';
            $notice['url_view'] = route('front.tblogs.show',$blog->slug);
            TNotice::create($notice);
        }
        if($data['item_code'] == 'blog')
        {
            $notice = array();
            $blog = \App\Models\Blog::findOrFail($data['item_id']);
            $notice['user_id'] = $blog->user_id;
            $notice['item_id'] =  $data['item_id'] ;
            $notice['item_code'] =  $data['item_code'] ;
            $user = User::find($data['user_id']);
            $notice['title'] =  $user->full_name .' thêm bình luận bài viết';
            $notice['url_view'] = route('front.page.view',$blog->slug);
            TNotice::create($notice);
        }
        if($data['item_code'] == 'congviec')
        {
            $notice = array();
            $congviec = JCongviec::findOrFail($data['item_id']);
            $notice['user_id'] = $congviec->user_id;
            $notice['item_id'] =  $data['item_id'] ;
            $notice['item_code'] =  $data['item_code'] ;
            $user = User::find($data['user_id']);
            $notice['title'] =  $user->full_name .' thêm bình luận việc làm';
            $notice['url_view'] = route('front.vieclam.chitietvieclam',$congviec->id);
            TNotice::create($notice);
        }
        if($data['item_code'] == 'ads')
        {
            $notice = array();
           
            $ad = \App\Models\Ads::find($data['item_id']);
            if($ad)
            {
                $ad->position += 1;
                $ad->save();
            }
    
            $notice['user_id'] = $ad->user_id;
            $notice['item_id'] =  $data['item_id'] ;
            $notice['item_code'] =  $data['item_code'] ;
            $user = User::find($data['user_id']);
            $notice['title'] =  $user->full_name .' thêm bình luận cắt lỗ xả hàng';
            $notice['url_view'] = route('front.ad.view',$ad->slug);
            TNotice::create($notice);
        }
        if($data['item_code'] == 'event') {
            $notice = [];
            $event = Event::findOrFail($data['item_id']);
            $notice['user_id']   = $event->user_id ?? null;
            $notice['item_id']   = $data['item_id'];
            $notice['item_code'] = $data['item_code'];
            $user = User::find($data['user_id']);
            $notice['title']     = $user->full_name . ' thêm bình luận sự kiện';
            $notice['url_view']  = route('front.events.show', $event->slug);
            TNotice::create($notice);
        }
        return $comment;
    }
    
    public function saveComment(Request $request)
    {
        
        $this->validate($request,[
            'content'=>'string|required',
            'item_id'=>'numeric|required',
            'item_code'=>'string|required',
            'parent_id'=>'numeric|required',
        ]);
        $data = $request->all();
        $user = auth()->user();
        if(!$user)
        {
            return response()->json(['msg'=>'chưa đăng nhập','status'=>false]);
        }
        $data['user_id'] = $user->id;
        $comment = $this->saveCommentArr($data);
        $comment->full_name = $user->full_name;
        $comment->photo = $user->photo;

        TUserpage::add_points(auth()->id(),1);


        return response()->json(['msg'=>$comment,'status'=>true]);
    }
    public function updateComment(Request $request)
    {
        
        $this->validate($request,[
            'content'=>'string|required',
            'id'=>'numeric|required',
        ]);
        $data = $request->all();
        $user = auth()->user();
        if(!$user)
        {
            return response()->json(['msg'=>'chưa đăng nhập','status'=>false]);
        }
        $comment = TComment::find($data['id']);
        if(!$comment)
        {
            return response()->json(['msg'=>'không tìm thấy dữ liệu','status'=>false]);
        }
        if($user->id != $comment->user_id)
        {
            return response()->json(['msg'=>'bạn không phải là tác giả','status'=>false]);
        }
        $comment->fill($data)->save();
        return response()->json(['msg'=>$comment,'status'=>true]);
    }
    public function deleteComment(Request $request)
    {
        
        $this->validate($request,[
            'id'=>'numeric|required',
        ]);
        $user= auth()->user();
        if(!$user)
        {
            return response()->json(['msg'=>'chưa đăng nhập','status'=>false]);
        }
        $comment = TComment::find($request->id);
        if(!$comment)
        {
            return response()->json(['msg'=>'không tìm thấy dữ liệu','status'=>false]);
        }
        if($user->id != $comment->user_id)
        {
            return response()->json(['msg'=>'bạn không phải là tác giả','status'=>false]);
        }
        // $comment->status = 'inactive';
        $comment->delete();
        return response()->json(['msg'=>'xóa thành công','status'=>true]);
    }

    public function vote(Request $request)
    {
        $request->validate([
            'item_id'=>'numeric|required',
            'item_code' => 'required|string', // Mã loại mục (ví dụ: 'blog', 'post')
            'point' => 'required|integer|min:1|max:5', // Điểm (1-5)
        ]);
    
        $userId = \Auth::id();
        if(!$userId)
            return response()->json(['success' => false, 'msg' =>  'Bạn cần đăng nhập!']);
        $itemCode = $request->input('item_code');
        $point = $request->input('point');
        $itemId = $request->item_id;
        $itemCode = $request->item_code;
        // Tìm bản ghi vote
        $voteRecord = DB::table('t_vote_items')
            ->where('item_id', $itemId)
            ->where('item_code', $itemCode)
            ->first();
       
            ///
        TUserpage::add_points(auth()->id(),1);

        if (!$voteRecord) {
            // Nếu chưa có bản ghi, tạo mới
            $votes = [$userId => $point];
            $data = [
                'item_id' => $itemId,
                'item_code' => $itemCode,
                'count' => 1,
                'point' => $point,
                'votes' => json_encode($votes),
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $voteRecord = TVoteItem::Create($data);
        } 
        
        // Nếu đã có bản ghi, cập nhật
        $votes = json_decode($voteRecord->votes, true);

        // Kiểm tra nếu user đã vote
        if (isset($votes[$userId])) {
            // Cập nhật số điểm của user
            $votes[$userId] = $point;
        } else {
            // Thêm user mới vào
            $votes[$userId] = $point;
        }

        // Cập nhật `count` và `point`
        $count = count($votes);
        $totalPoints = array_sum($votes);
        $averagePoint = $totalPoints / $count;

        DB::table('t_vote_items')
            ->where('item_id', $itemId)
            ->where('item_code', $itemCode)
            ->update([
                'count' => $count,
                'point' => $averagePoint,
                'votes' => json_encode($votes),
                'updated_at' => now(),
            ]);
        
    
        return response()->json(['success' => true, 'averagePoint' =>  $averagePoint,'count'=>$count]);
    }
    public function toggleBookmark(Request $request )
    {
        if(!auth()->id())
        {
            return response()->json(['success' => false, 'msg' => 'Bạn phải đăng nhập']);
        }
        $request->validate([
            'item_id' => 'required|integer', 
            'item_code'=> 'required|string', 
           
        ]);
        $data= $request->all();
        $userId = auth()->id(); // Lấy ID của người dùng hiện tại
        $itemCode = $data['item_code']; // Loại mục (ví dụ: blog)
        $postId = $data['item_id'];

        ///
        TUserpage::add_points(auth()->id(),1);
        
        // Kiểm tra xem bài viết đã được bookmark chưa
        $bookmarkExists = \DB::table('t_recommends')
            ->where('user_id', $userId)
            ->where('item_id', $postId)
            ->where('item_code', $itemCode)
            ->exists();
    
        if ($bookmarkExists) {
            // Nếu đã bookmark, xóa bookmark
            \DB::table('t_recommends')
                ->where('user_id', $userId)
                ->where('item_id', $postId)
                ->where('item_code', $itemCode)
                ->delete();
    
            $status = 'removed';
        } else {
            // Nếu chưa bookmark, thêm bookmark
            \DB::table('t_recommends')->insert([
                'user_id' => $userId,
                'item_id' => $postId,
                'item_code' => $itemCode,
                'created_at' => now(), // Nếu cần thêm thời gian
                'updated_at' => now(), // Nếu cần thêm thời gian
            ]);
    
            $status = 'added';
        }
    
        return response()->json(['status' => $status]);
    }
    public function react(Request $request)
    {
        
        if(!auth()->id())
        {
            return response()->json(['success' => false, 'msg' => 'Bạn phải đăng nhập']);
        }
        $request->validate([
            'item_id' => 'required|integer', 
              'item_code'=> 'required|string', 
            'reaction_id' => 'required|string', // Ví dụ: 'like', 'love', ...
        ]);
        $validReactions = TMotion::pluck('title')->toArray();
        ///
        TUserpage::add_points(auth()->id(),1);
        // Lưu lại danh sách reactions
        $motionitem = TMotionItem::where('item_id', $request->item_id)->where('item_code',$request->item_code)->first();
        if(!$motionitem)
        {
            $data['item_id'] = $request->item_id;
            $data['item_code'] = $request->item_code;
            $motionitem = TMotionItem::create($data);
           
        }
       
        $reactionType = $request->input('reaction_id');
        $reactions = $motionitem->motions ?? array_fill_keys($validReactions, 0);
        // Tăng số lượng reaction tương ứng
        if (isset($reactions[$reactionType])) {
            $reactions[$reactionType]++;
        }
       
        $motionitem->motions = $reactions;
       
        $userId = auth()->id(); // ID người dùng hiện tại
      
        $userreactions = $motionitem->user_motions??  array_fill_keys($validReactions, []);
        foreach ($userreactions as $key => $userIds) {
            $userreactions[$key] = array_filter($userIds, fn($id) => $id != $userId);
        }

         // Thêm ID người dùng vào reaction mới
 
        if (isset($userreactions[$reactionType])) {
            $userreactions[$reactionType][] = $userId;
        }
        
        foreach ($userreactions as $key => $values) {
            $reactions[$key] = count(array_filter($values, function ($value) {
                return !is_null($value); // Bỏ qua các giá trị null
            }));
        }
        $motionitem->user_motions = $userreactions;
        $motionitem->motions = $reactions;
        $motionitem->save();

        return response()->json(['success' => true, 'reactions' => $motionitem->motions ]);
    }
}