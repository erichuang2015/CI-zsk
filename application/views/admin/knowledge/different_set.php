<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>菜单管理</title>
    <script src="/public/js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script src="/public/layui/layui.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/public/layui/css/layui.css"  media="all">
</head>
<style>
    .list1 { margin-right:20px;}
</style>
<script>
    $(document).ready(function(){
        layui.use(['layer'], function(){
            var layer = layui.layer;
            //新增操作
            $('#add').click(function(){
                layer.open({
                    type: 2,
                    title:"添加分类",
                    area: ['700px', '220px'],
                    fixed: false, //不固定
                    maxmin: true,
                    content: '/admin/knowledge/different/different_add/'
                });
            });

            //删除操作
            $(".del").click(function(){
                var st = confirm('分类下可能还存在文章!确定删除吗?');
                if(st == false){ return false;}
                var id = $(this).closest('tr').find('.gid').html();
                $.post("/admin/knowledge/different/different_del/", { id: id },
                    function(ee){
                        if(ee.status == 1){
                            layer.alert(ee.msg,{icon: 6},function(index)
                            {
                                window.location.reload();//刷新当前页面
                            });
                        }else{
                            layer.alert(ee.msg, {icon: 5,skin: 'layer-ext-moon' });
                        }
                    },'json');

            });

              //批量删除
              $("#delall").click(function(){
                    var aa = $(this).closest('body').find('table .aa').find('input:checked');
                    if(aa.length < 1){
                        layer.alert('请勾选要分享的文章', {icon: 5,skin: 'layer-ext-moon' });
                        return false;
                    }
                    var valArr = new Array;
                    aa.each(function(i){
                        valArr[i] = $(this).val();
                    });
                    var vals = valArr.join(',');//转换为逗号隔开的字符串
                    //ajax方法传递到后台 进行业务删除；
//                $.post("/admin/knowledge/different/different_add",$("#addt").serialize(),
                    $.post("/admin/knowledge/different/different_del/", { id:vals },
                        function(data){
                            if(data.status == 1){
                                layer.alert(data.msg,{icon: 6},function(index)
                                {
                                    window.location.reload();//刷新当前页面
                                });
                            }else{
                                layer.alert(data.msg, {icon: 5,skin: 'layer-ext-moon' });
                            }
                        },'json');
                    return false;
                });
                
            //修改操作
            $('.update').click(function(){
                var id = $(this).closest('tr').find('.gid').html();
                layer.open({
                    type: 2,
                    title:"修改分类",
                    area: ['700px', '220px'],
                    fixed: false, //不固定
                    maxmin: true,
                    content: '/admin/knowledge/different/different_update/'+id
                });
            });

              //全选或全不选
              $("#checkAll").change(function(){
                if(this.checked){
                    $('tr :checkbox').prop('checked',true);
                }else{
                    $('tr :checkbox').prop('checked',false);
                }
            });
            $('#refresh').click(function() { window.location.reload();});

        });
    });
</script>
<style>
* { font-family:"微软雅黑" !improtant;}
.hsl  { height:35px; font-size:13px; line-height:35px;width:102%;}
.xxx { height:34px;line-height:34px;}
.aa td { font-size:13px !important;}
</style>
<body>
<!-- <button class="layui-btn" id="add">新增分类</button> -->

<blockquote class="layui-elem-quote">
    <form style="float:left;" method="get" action="/admin/knowledge/different/different_set/" >
        <div class="layui-input-inline">
            <input type="text" name="kw" value=""  lay-verify="required" placeholder="请输入分类名称/发布人" autocomplete="off" class="layui-input hsl">
        </div>&nbsp;
        <!-- <button class="layui-btn layui-btn-normal">分类搜索</button> -->
        <button class="layui-btn layui-btn-normal layui-btn-sm xxx"><i class="layui-icon"></i> 搜索</button>
    </form>
    <!-- <button class="layui-btn layui-btn-warm" style="float:left; margin-left: 20px;" id="add">新增分类</button> -->
    <button class="layui-btn layui-btn-small layui-btn-warm xxx" style="float:left; margin-left: 20px;" id="add"><i class="layui-icon"></i>新增分类</button>
    &nbsp;&nbsp;
    <!-- <button class="layui-btn" id="delall">批量删除</button> -->
    <button class="layui-btn layui-btn-sm layui-btn-danger xxx" id="delall"><i class="layui-icon"></i>批量删除</button>
    <button class="layui-btn layui-btn-sm layui-bg-cyan xxx" id="refresh"><i class="layui-icon">&#x1002;</i> 刷新页面</button>
    <div style="clear: both"></div>
</blockquote>
<div>
    <table class="layui-table">
        <colgroup>
            <col width="150">
            <col width="150">
            <col width="200">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th><input type="checkbox" id="checkAll" name="id[]" style="width:16px; height:16px; float:left;"><span style="margin-top: -2px;display: block;float: left; margin-left: 10px;">请选择</span></th>
            <th>分类编号</th>
            <th>分类名称</th>
            <th>发布人</th>
            <th>添加时间</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($situation)): ?>
        <?php foreach($situation as $k => $v):  ?>
        <tr class="aa">
            <td><input type="checkbox" id="child" name="id[]" value="<?php echo $v['id']?$v['id']:'-' ?>" style="width:16px; height:16px;"> </td>
            <td class="gid"><?php echo $v['id']?$v['id']:'--' ?></td>
            <td><?php echo $v['tname']?$v['tname']:'--' ?></td>
            <td><?php echo $v['author']?$v['author']:'--' ?></td>
            <td><?php echo $v['addtime']?date('Y-m-d H:i:s',$v['addtime']):'--' ?></td>
            <td><?php echo $v['sort']?$v['sort']:'--' ?></td>
            <td>
                <!-- <a href="javascript:void(0);" class="del"><i class="layui-icon" style="font-size: 30px; color: #1E9FFF;">&#xe640;</i> </a>&nbsp;&nbsp;
                <a href="javascript:void(0);" class="update"><i class="layui-icon" style="font-size: 30px; color: #1E9FFF;">&#xe642;</i></a> -->
                <a lay-event="edit" class="update layui-btn layui-btn-xs">编辑</a>
                <a lay-event="edit" class="del layui-btn layui-btn-xs layui-btn-danger">删除</a>
            </td>
        </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan=7 style="text-align: center;">
                    <span style="color:red; text-align: center; line-height: 25px;"><i class="layui-icon" style="font-size: 25px; color:red;">&#xe69c;</i>  抱歉!没有您想要的内容!</span>
                </td
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
<div class="page" style="margin-top:-5px;"><?php echo $this->pagination->create_links();?>&nbsp;&nbsp;共 <?php echo $total_rows;?> 条记录</div>











</body>
</html>