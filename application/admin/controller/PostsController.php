<?php
namespace app\admin\controller;
use app\admin\model\Administrator;
use app\admin\model\Posts;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
class PostsController extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '文章',
		'module_url'  => '/admin/posts/',
		'module_slug' => 'posts',
		'upload_path' => UPLOAD_PATH,
		'upload_url'  => '/public/uploads/',
        'ckeditor'    => array(
            'id'     => 'ckeditor-post_content',
            //Optionnal values
            'config' => array(
                'width'  => "100%", //Setting a custom width
                'height' => '400px',
                // 默认调用 Standard Package，以下代码为调用自定义工具栏，这些基础的主要用于前台用户富文本设置
                // 'toolbar'   =>  array(  //Setting a custom toolbar
                //     array('Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates'),
                //     array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'),
                //     array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'),
                //     array('Styles','Format','Font','FontSize'),
                //     array('TextColor','BGColor')
                // )
            )
        ),
	);


    /**
     * [index 获取文章数据列表]
     * @return [type] [description]
     */
    public function index()
    {
        /*
        *   关联查询admin nickname
        *   Model 中设置了 getPostAuthorAttr 属性读取器，所以不需要用关联查询，
        *   或者可以取消属性读取器，用关联查询，但是由于没有设置属性读取器，
        *   在 create/read 页面,select/checkbox/radio字段默认值判断时不对，需要单独设置默认值
        */
        // $list =  Posts::view('posts','*')
        //                 ->view('administrator',['nickname'],'posts.post_author=administrator.id') //这里本人对关联查询写法不熟，手册中关联查询部分没有完整实例，试了几种方法（join(),model定义关联），最后用view写
        //                 ->where('posts.status','>=','0')
        //                 ->order('posts.create_time', 'DESC')
        //                 ->paginate();

        //直接查询,注：getPostAuthorAttr 中已经得到了 post_author 名称
        $request = request();
        $param = $request->param();

        $map['status'] = ['>=','0'];

        if(!empty($param)){
            $this->data['search'] = $param;
            if(isset($param['title'])){
                $map['post_title'] = ['like','%'.$param['title'].'%'];
            }

            if(isset($param['start_time']) || isset($param['end_time'])){
                if(isset($param['start_time']) && isset($param['end_time'])){
                    $map['create_time'] = ['between time',[$param['start_time'],$param['end_time']]];
                }

                if(isset($param['start_time']) && !$param['end_time']){
                    $map['create_time'] = ['>= time',$param['start_time']];
                }
                if(isset($param['end_time']) && !$param['start_time']){
                    $map['create_time'] = ['<= time',$param['end_time']];
                }
            }
        }


        $list =  Posts::where($map)
                        ->order('create_time', 'DESC')
                        ->paginate();

        $this->assign('data',$this->data);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * [create 创建文章数据页面]
     * @return [type] [description]
     */
    public function create()
    {
        $admins = Administrator::where('status',1)->column('nickname','id');

        $this->data['edit_fields'] = array(
            'post_title'     => array('type' => 'text', 'label' => '标题'),
            'post_excerpt'   => array('type' => 'textarea', 'label' => '摘要'),
            'post_content'   => array('type' => 'textarea', 'label' => '内容'),
            'feature_image'  => array('type' => 'file','label'     => '特色图片'),
            'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '草稿', 1 => '发布',2 => '待审核')),
            'hr1'            => array('type' => 'hr'),
            'alert1'         => array('type' => 'alert', 'default' => '其它信息'),
            'post_author'    => array('type' => 'select', 'label' => '作者','default' => $admins, 'extra'=>array('wrapper'=>'col-sm-3')),
            'post_password'  => array('type' => 'text', 'label' => '访问密码','notes'=>'默认不填则可以直接访问', 'extra'=>array('wrapper'=>'col-sm-3')),
            'comment_status' => array('type' => 'select', 'label' => '评论开关', 'default' => array('opened'=>'打开','closed' => '关闭'), 'extra'=>array('wrapper'=>'col-sm-3')),
            'create_time'    => array('type' => 'text', 'label' => '发布时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD hh:mm:ss'),'wrapper'=>'col-sm-3')),
            'hr2'            => array('type' => 'hr'),
        );

        //默认值设置
        $item['status']         = '发布';
        $item['comment_status'] = config('comment_toggle') ? '打开' : '关闭';
        $item['create_time']    = date('Y-m-d H:i:s');

        $this->assign('item',$item);
        $this->assign('data',$this->data);
        return view();
    }

    /**
     * [add 新增文章数据ACTION，create()页面表单数据提交到这里]
     * @return [type] [description]
     */
    public function add()
    {
        $posts = new Posts;
        $data = input('post.');

        $rule = [
            'post_title|文章标题' => 'require',
            'status|文章状态' => 'require',
            'post_author|文章作者' => 'require',
            'comment_status|评论开关' => 'require',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }

        $data['feature_image'] = $this->upload();
        if(!$data['feature_image']){
            unset($data['feature_image']);
        }

        $data['create_time'] = $data['create_time'] ? strtotime($data['create_time']) : time();
        $data['update_time'] = time();


        if ($id = $posts->validate(true)->insertGetId($data)) {
            return $this->success('文章添加成功',$this->data['module_url'].$id);
        } else {
            return $this->error($posts->getError());
        }
    }



    /**
     * [read 读取文章数据]
     * @param  string $id [文章ID]
     * @return [type]     [description]
     */
    public function read($id='')
    {
        $admins = Administrator::where('status',1)->column('nickname','id');

        $this->data['edit_fields'] = array(
            'post_title'     => array('type' => 'text', 'label' => '标题'),
            'post_excerpt'   => array('type' => 'textarea', 'label' => '摘要'),
            'post_content'   => array('type' => 'textarea', 'label' => '内容'),
            'feature_image'  => array('type' => 'file','label'     => '特色图片'),
            'status'         => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '草稿', 1 => '发布',2 => '待审核')),
            'hr1'            => array('type' => 'hr'),
            'alert1'         => array('type' => 'alert', 'default' => '其它信息'),
            'post_author'    => array('type' => 'select', 'label' => '作者','default' => $admins, 'extra'=>array('wrapper'=>'col-sm-4')),
            'post_password'  => array('type' => 'text', 'label' => '访问密码','notes'=>'默认不填则可以直接访问', 'extra'=>array('wrapper'=>'col-sm-4')),
            'comment_status' => array('type' => 'select', 'label' => '评论开关', 'default' => array('opened'=>'打开','closed' => '关闭'), 'extra'=>array('wrapper'=>'col-sm-4')),
            'clearfix1'      => array('type' => 'clearfix'),
            'create_time'    => array('type' => 'text', 'label' => '发布时间','class'=>'datepicker','extra'=>array('data'=>array('format'=>'YYYY-MM-DD hh:mm:ss'),'wrapper'=>'col-sm-4')),
            'update_time'    => array('type' => 'text', 'label' => '更新时间','disabled'=>true, 'extra'=>array('wrapper'=>'col-sm-4')),
            'comment_count'    => array('type' => 'text', 'label' => '评论数','disabled'=>true, 'extra'=>array('wrapper'=>'col-sm-4')),
            'hr2'            => array('type' => 'hr'),
        );

        //默认值设置
        $item = Posts::get($id);
        $item['post_content'] = str_replace('&', '&amp;', $item['post_content']);

        $this->assign('item',$item);
        $this->assign('data',$this->data);

        return view();
    }

    /**
     * [update 更新文章数据，read()提交表单数据到这里]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update($id)
    {
        $posts = new Posts;
        $data = input('post.');

        $rule = [
            //字段验证
			'post_title|文章标题' => 'require',
            'status|文章状态' => 'require',
            'post_author|文章作者' => 'require',
            'comment_status|评论开关' => 'require',
        ];
        $msg = [];

        // 数据验证
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }

        $data['id'] = $id;

        $data['feature_image'] = $this->upload();
        if(!$data['feature_image']){
        	unset($data['feature_image']);
        }

        if ($posts->update($data)) {
            return $this->success('信息更新成功',$this->data['module_url'].$id);
        } else {
            return $posts->getError();
        }
    }

    /**
     * [upload 图片上传]
     * @return [type] [description]
     */
    public function upload(){
	    // 获取表单上传文件
	    $file = request()->file('feature_image');
	    if($file){
	        if (true !== $this->validate(['feature_image' => $file], ['feature_image' => 'image'])) {
	            $this->error('请选择图像文件');
	        } else {
	        	$info = $file->rule('uniqid')->move(ROOT_PATH . 'public' . DS . 'uploads'); //保存原图

	            // 读取图片
	            $image = Image::open($file);
	            // 图片处理
	            $image_type = request()->param('type') ? request()->param('type') : 1;
	            switch ($image_type) {
	                case 1: // 缩略图
	                    $image->thumb(150, 150, Image::THUMB_CENTER);
	                    break;
	                case 2: // 图片裁剪
	                    $image->crop(300, 300);
	                    break;
	                case 3: // 垂直翻转
	                    $image->flip();
	                    break;
	                case 4: // 水平翻转
	                    $image->flip(Image::FLIP_Y);
	                    break;
	                case 5: // 图片旋转
	                    $image->rotate();
	                    break;
	                case 6: // 图片水印
	                    $image->water(ROOT_PATH . 'public/static/images/logo.png', Image::WATER_NORTHWEST, 50);
	                    break;
	                case 7: // 文字水印
	                    $image->text('ThinkPHP', VENDOR_PATH . 'topthink/think-captcha/assets/ttfs/1.ttf', 20, '#ffffff');
	                    break;
	            }
	            $this->sourceFile = $info->getFilename();

	            $fileName = explode('.',$info->getFilename());
	            $saveName = $fileName[0] . '_thumb.' .$info->getExtension();
	            $image->save($this->data['upload_path'] .'/'. $saveName);

	            $this->imageThumbName = $saveName;

	            return $this->imageThumbName;
	        }
	    }else{
	     	return false;
	    }
	}

    /**
     * [delete 删除文章数据(伪删除)]
     * @param  [type] $id [表ID]
     * @return [type]     [description]
     */
    public function delete($id)
    {
        $posts = new Posts;
        $data['id'] = $id;
        $data['status'] = -1;
        if ($posts->update($data)) {
        	$data['error'] = 0;
        	$data['msg'] = '删除成功';
        } else {
        	$data['error'] = 1;
        	$data['msg'] = '删除失败';
        }
        return $data;

        // 真.删除，不想用伪删除，请用这段(TODO：增加回收站功能用，在回收站清空时用真删除)
        // $posts = Posts::get($id);
        // if ($posts) {
        //     $posts->delete();
        //     $data['error'] = 0;
        // 	$data['msg'] = '删除成功';
        // } else {
        // 	$data['error'] = 1;
        // 	$data['msg'] = '删除失败';
        // }
        // return $data;
    }

    public function delete_image($id){
    	$posts = Posts::get($id);
    	if (file_exists($this->data['upload_path'] .'/'. $posts->feature_image)) {
            @unlink($this->data['upload_path'] .'/'. $posts->feature_image);
        }

        $source_image = str_replace('_thumb', '', $posts->feature_image);
        if (file_exists($this->data['upload_path'] .'/'. $source_image)) {
            @unlink($this->data['upload_path'] .'/'. $source_image);
        }

        $data['id'] = $id;
        $data['feature_image'] = '';
        if ($posts->update($data)) {
        	return $this->success('图像删除成功',$this->data['module_url'].$id);
        }else{
        	return $posts->getError();
        }


    }
}