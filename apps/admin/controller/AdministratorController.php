<?php
namespace app\admin\controller;
use app\admin\model\Administrator;
use app\admin\controller\AdminAuth;
use think\Validate;
use think\Image;
use think\Request;
class AdministratorController extends AdminAuth
{
	//模块基本信息
	private $data = array(
		'module_name' => '管理员',
		'module_url'  => '/admin/administrator/',
		'module_slug' => 'administrator',
		'upload_path' => ROOT_PATH . 'public' . DS . 'uploads',
		'upload_url'  => '/public/uploads/',
		);


    /**
     * [index 获取用户数据列表]
     * @return [type] [description]
     */
    public function index()
    {
        $list =  Administrator::where('status','>=','0')->order('id', 'ASC')->paginate(10);

        $this->assign('data',$this->data);
        $this->assign('list',$list);
        return $this->fetch();
    }

    /**
     * [create 创建用户数据页面]
     * @return [type] [description]
     */
    public function create()
    {
    	$this->data['edit_fields'] = array(
			'username' => array('type' => 'text', 'label'     => '用户名'),
			'nickname' => array('type' => 'text', 'label'     => '用户昵称'),
			'password' => array('type' => 'password', 'label' => '密码','notes'=>'更新管理员资料时默认不填则不修改'),
			'salt'     => array('type' => 'text', 'label'     => '加密盐'),
			'mobile'   => array('type' => 'text', 'label'     => '手机号'),
			'avatar'   => array('type' => 'file','label'     => '头像'),
			'status'   => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '禁用', 1 => '正常', 2 => '待审核')),
        );
        $this->assign('data',$this->data);
        return view();
    }

    /**
     * [add 新增用户数据ACTION，create()页面表单数据提交到这里]
     * @return [type] [description]
     */
    public function add()
    {
        $user = new Administrator;
        $data = input('post.');

        $rule = [
            //管理员登陆字段验证
            'nickname|昵称'   => 'require|min:2',
			'username|用户名' => 'require|alphaDash|min:5|unique:administrator',
			'password|密码'   => 'require|min:5',
			'mobile|手机号'   => 'length:11',
			'salt|加密盐'     => 'length:3|number',
        ];
        // 数据验证
        $validate = new Validate($rule);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }

        $data['avatar'] = $this->upload();

        $data['password'] =  (isset($data['salt']) && $data['salt']) ? md5($data['password'].$data['salt']) : md5($data['password']);
        $data['create_time'] = time();
        $data['update_time'] = time();


        if ($id = $user->validate(true)->insertGetId($data)) {
            return $this->success('用户添加成功',$this->data['module_url'].$id);
        } else {
            return $this->error($user->getError());
        }
    }



    /**
     * [read 读取用户数据]
     * @param  string $id [用户ID]
     * @return [type]     [description]
     */
    public function read($id='')
    {
        $this->data['edit_fields'] = array(
			'username' => array('type' => 'text', 'label'     => '用户名'),
			'nickname' => array('type' => 'text', 'label'     => '用户昵称'),
			'password' => array('type' => 'password', 'label' => '密码','notes'=>'更新管理员资料时默认不填则不修改'),
			'salt'     => array('type' => 'text', 'label'     => '加密盐 (3位数)','notes'=>'加密盐更新时，必需同时更新密码，加密盐不可单独更新'),
			'mobile'   => array('type' => 'text', 'label'     => '手机号'),
			'avatar'   => array('type' => 'file','label'     => '头像'),
			'status'   => array('type' => 'radio', 'label' => '状态','default'=> array(-1 => '删除', 0 => '禁用', 1 => '正常', 2 => '待审核')),
        );
        $item = Administrator::get($id);
        $this->assign('data',$this->data);
        $this->assign('item',$item);
        return view();
    }

    /**
     * [update 更新用户数据，read()提交表单数据到这里]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update($id)
    {
        $user = new Administrator;
        $data = input('post.');

        $preview = $user->where(array('username'=>$data['username']))->find();


        $rule = [
            //管理员登陆字段验证
            'nickname|昵称'   => 'require|min:2',
			'username|用户名' => 'require|alphaDash|min:5|unique:administrator,username,'.$id.',id', //更新时用户名唯一，但排除当前ID, 由于用的伪删除，实际情况中还应排除status=-1的情况，不要问这里为什么没写，因为我不会（官方手册中没看到）！
			'mobile|手机号'   => 'length:11',
			'salt|加密盐'     => 'length:3|number',
        ];
        $msg = [];

        //加密盐更新时，必需同时更新密码，加密盐不可单独更新
        if($preview['salt'] != $data['salt']){
        	$rule['password|密码'] = 'require|min:5';
        	$msg['password.require'] = '加密盐更新时，必需同时更新密码，加密盐不可单独更新';
        }else{
        	$rule['password|密码']  = 'min:5'; //更新时不验证必填，只在填写的时候验证长度
        }


        // 数据验证
        $validate = new Validate($rule,$msg);
        $result   = $validate->check($data);
        if(!$result){
            return  $validate->getError();
        }
        if(input('password')){
	        $data['password'] =  (isset($data['salt']) && $data['salt']) ? md5($data['password'].$data['salt']) : md5($data['password']);
	    }else{
	    	unset($data['password']);
	    }
        $data['id'] = $id;

        $data['avatar'] = $this->upload();
        if(!$data['avatar']){
        	unset($data['avatar']);
        }

        if ($user->update($data)) {
            return $this->success('管理员信息更新成功',$this->data['module_url'].$id);
        } else {
            return $user->getError();
        }
    }

    /**
     * [upload 图片上传]
     * @return [type] [description]
     */
    public function upload(){
	    // 获取表单上传文件
	    $file = request()->file('avatar');
	    if($file){
	        if (true !== $this->validate(['avatar' => $file], ['avatar' => 'image'])) {
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
     * [delete 删除用户数据(伪删除)]
     * @param  [type] $id [表ID]
     * @return [type]     [description]
     */
    public function delete($id)
    {
        $user = new Administrator;
        $data['id'] = $id;
        $data['status'] = -1;
        if ($user->update($data)) {
        	$data['error'] = 0;
        	$data['msg'] = '删除成功';
        } else {
        	$data['error'] = 1;
        	$data['msg'] = '删除失败';
        }
        return $data;

        // 真.删除，不想用伪删除，请用这段
        // $user = Administrator::get($id);
        // if ($user) {
        //     $user->delete();
        //     $data['error'] = 0;
        // 	$data['msg'] = '删除成功';
        // } else {
        // 	$data['error'] = 1;
        // 	$data['msg'] = '删除失败';
        // }
        // return $data;
    }

    public function delete_image($id){
    	$user = Administrator::get($id);
    	if (file_exists($this->data['upload_path'] .'/'. $user->avatar)) {
            @unlink($this->data['upload_path'] .'/'. $user->avatar);
        }

        $source_image = str_replace('_thumb', '', $user->avatar);
        if (file_exists($this->data['upload_path'] .'/'. $source_image)) {
            @unlink($this->data['upload_path'] .'/'. $source_image);
        }

        $data['id'] = $id;
        $data['avatar'] = '';
        if ($user->update($data)) {
        	return $this->success('图像删除成功',$this->data['module_url'].$id);
        }else{
        	return $user->getError();
        }


    }

    public function update_expire_time($id)
    {
        $user = Administrator::get($id);
        $data['id'] = $id;
        $user->expire_time = time(); //
        if (false !== $user->save()) {
        	$data['expire_time'] = $user->expire_time;
        	$data['error'] = 0;
        	$data['msg'] = '更新成功';
        } else {
        	$data['error'] = 1;
        	$data['msg'] = '更新失败';
        }
        return $data;
    }
}