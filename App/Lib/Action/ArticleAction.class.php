<?php
/**
 * 文章显示页面
 * @author liuhui
 * @since 2013.11.15
 */
class ArticleAction extends CommonAction{
    
    // 构造函数
    public function __construct() {
        $catM = new CatModel();
        $catInfo = $catM->select();
        foreach($catInfo as $key=>$catV){
            $catInfo[$key]['url'] = U('article/index',array('catid'=>$catV['id']));
        }
        parent::__construct();
        
        $commentM   = new CommentModel();
        $commentInfo= $commentM->order('id desc')->limit(10)->select();
        foreach($commentInfo as $key=>$comment){
            $encodeId = $this->encodeArticleId($comment['article_id']);
            $commentInfo[$key]['article_id'] = $encodeId;
        }
        $this->assign('commentInfo', $commentInfo);
        $this->assign('catInfo', $catInfo);
    }
    // 整个页面的首页
    public function index()
    {
        $articleM = new ArticleModel; // 实例化User对象
        import('ORG.Util.Page');// 导入分页类
        $where = array();
        $where['status'] = '正常';
        if(isset($_GET['catid']))
            $where['cat_id'] = intval($_GET['catid']);
        
        $count      = $articleM->where($where)->count();// 查询满足要求的总记录数
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = ($count>= 10) ? $Page->show() : '';// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $articleList = $articleM->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        foreach($articleList as $key=>$val)
        {
            $encodeId = $this->encodeArticleId($val['id']);
            $articleList[$key]['id'] = $encodeId;
            $articleList[$key]['url'] = U('article/detail',array('id'=>$encodeId));
            $content = strip_tags($val['content']);
            $articleList[$key]['content'] = (strlen($content) > 600) ? mb_substr($content,0,600,'UTF-8').'......' : $content;
        }
        $this->assignSeoName('php、mysql技术交流、网站制作');
        $this->assign('articleList',$articleList);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    // 文章详情
    public function detail()
    {
        $id = intval($_GET['id']);
        $articleId  = $this->decodeAritcleId($id);
        if($articleId) {
            $articleM   = new ArticleModel();
            $articleInfo = $articleM->find($articleId);
            $commentM   = new CommentModel();
            $commentInfo= $commentM->where(array('article_id'=>$articleId))->select();
            
            $articleM->where('id='.$articleId)->setInc('click',1); 
            $this->assignSeoName($articleInfo['title'], $articleInfo['tag'], $articleInfo['tag']);
            $this->assign('commentInfo', $commentInfo);
            $this->assign('articleInfo', $articleInfo);
        }else{
            $this->error('文章不存在', U('article/index'));
        }
        
        $token = session_id().'_'.$articleId;
        $this->assign('token', $token);
        $this->display();
    }
    
    // 关于我
    public function aboutme()
    {
        $this->assignSeoName('关于我');
        $this->display();
    }
    
    // ajax回复消息
    public function ajaxcomment()
    {
        $articleId      = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
        $email          = isset($_POST['email']) ? $_POST['email'] : '';
        $personalSite   = isset($_POST['personal_site']) ? $_POST['personal_site'] : '';
        $comment        = isset($_POST['comment']) ? $_POST['comment'] : '';
        $token = isset($_POST['token']) ? $_POST['token'] : '';
        $userToken = session_id().'_'.$articleId;
        
        $arr = array(
            'succ' => 'F',
            'msg' => ''
        );
        if($token != $userToken){
            $arr['msg'] = '插入错误';
        }else{
        
            $commentM = new CommentModel();
            $res = $commentM->add(array(
                'article_id' => $articleId,
                'email'     => $email,
                'personal_site' => $personalSite,
                'comment'   => $comment,
                'add_time'  => time(),
            ));
            if($res) {
                $arr['succ'] = 'T';
                $msg = '<div class="comment_body">
                      <div class="comment_time">
                          '.$email.' 回答于'.date('Y-m-d H:i').'
                      </div>
                      <div class="comment_detail">
                          '.$comment.'
                      </div>
                  </div>';
                $arr['msg'] = $msg;
            }else{
                $arr['msg'] = '插入数据错误';
            }
        }
        echo json_encode($arr);
        exit;
    }
    
    // 搜索
    function search()
    {
        $searchName = isset($_GET['key_words']) ? trim($_GET['key_words']) : '';
        $articleM = new ArticleModel; // 实例化User对象
        import('ORG.Util.Page');// 导入分页类
        $where = array();
        $where['status'] = '正常';
        
        $where['title'] = array('like','%'.$searchName.'%');
        
        $count      = $articleM->where($where)->count();// 查询满足要求的总记录数
        $Page       = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show       = ($count>= 10) ? $Page->show() : '';// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $articleList = $articleM->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        foreach($articleList as $key=>$val)
        {
            $articleList[$key]['url'] = U('article/detail',array('id'=>$this->encodeArticleId($val['id'])));
//            $articleList[$key]['id'] = $this->encodeArticleId($val['id']);
            $content = strip_tags($val['content']);
            $articleList[$key]['title']   = searchWrodsDisplay($val['title'], $searchName);
            $articleList[$key]['content'] = (strlen($content) > 600) ? mb_substr($content,0,600,'UTF-8').'......' : $content;
        }
        $this->assign('articleList',$articleList);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
}
?>
