<?php  if (!defined('__TYPECHO_ROOT_DIR__')) exit; 
/**
 * Sitemap(纯文本模式：url?txt=1)
 * 
 * @package custom 
 * 
 */
?>
<?php 
        $db = Typecho_Db::get();
        $options = Typecho_Widget::widget('Widget_Options');
        $posts= $db->fetchAll($db->select()->from('table.contents')
                ->where('table.contents.status = ?', 'publish')
                ->order('table.contents.created', Typecho_Db::SORT_DESC));
        $is_txt=false;
        //搜索引擎限制sitemap文件最多有50,000条记录
        $list_num=50000;
        $sitemap_list = "";
        //相对于其他页面的优先权
        $priority ="0.8";
        //页面内容更新频率:always,hourly,daily,weekly,monthly,yearly,never
        $changefreq = "daily";
        //增加纯文本地址列表
        if (isset($_GET['txt'])) {$is_txt=true;}
        
        //获取文章信息
        foreach($posts as $p){
            /** 取出所有分类 */
            $p['categories'] = $db->fetchAll($db
            ->select()->from('table.metas')
            ->join('table.relationships', 'table.relationships.mid = table.metas.mid')
            ->where('table.relationships.cid = ?', $p['cid'])
            ->where('table.metas.type = ?', 'category')
            ->order('table.metas.order', Typecho_Db::SORT_ASC));

            /** 取出第一个分类作为slug条件 */
            $p['category'] = current(Typecho_Common::arrayFlatten($p['categories'], 'slug'));
            //去掉附件
            $type = $p['type'];
            if($type == "post"){
                $routeExists = (NULL != Typecho_Router::get($type));
                $pathinfo = $routeExists ? Typecho_Router::url($type, $p) : '#';
                $permalink = Typecho_Common::url($pathinfo, $options->index);
                if($is_txt){
                    $sitemap_list=$sitemap_list.$permalink . "\n\r<br>";
                }else {
                    $sitemap_list=$sitemap_list."\t<url>\n\t\t<loc>" . $permalink ."</loc>\n"
                    . "\t\t<lastmod>" . date('Y-m-d\TH:i:s+08:00',$p['created']) . "</lastmod>\n"
                    . "\t\t<changefreq>" . $changefreq . "</changefreq>\n"
                    . "\t\t<priority>" . $priority . "</priority>\n"
                    . "\t</url>\n";
                }
                $list_num--;
                if($list_num<=0){
                    break;
                }
            }
        }
        if(!$is_txt){
            header("Content-Type: application/xml");
            $sitemap_list ="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n <urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n" . $sitemap_list . "</urlset>";
        }
        echo $sitemap_list;
    ?>    
