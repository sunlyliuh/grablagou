<?php
$this->breadcrumbs=array(
	'Jobs',
);

?>

<h1>analysis</h1>
<!-- 为 ECharts 准备一个具备大小（宽高）的Dom -->
<div>
    <select name="city" id="city">
        <option value="1">北京</option>
        <option value="2">上海</option>
        <option value="3">广州</option>
        <option value="4">深圳</option>
        <option value="5">杭州</option>
        <option value="6">成都</option>
        <option value="7">武汉</option>
    </select>
<!--    <select name="job_type" id="job_type">
        <option value="1">php</option>
        <option value="2">Java</option>
        <option value="3">c</option>
        <option value="4">C++</option>
        <option value="5">Android</option>
        <option value="6">iOS</option>
    </select>-->
    <input type="button" name="sub_search" id="sub_search" value="搜索" />
</div>
<!-- 同一个城市不同职位的发布需求-->
<div id="result_data" style="width: 800px;height:500px;"></div>
<!-- 同一个城市不同职位的最高平均工资-->
<div id="result_max_salary" style="width: 800px;height:500px;"></div>
<script type="text/javascript">
$(document).ready(function(){
    $("#sub_search").click(function(){
        var city = $("#city").val();
        var url = '/index.php?r=job/cityanalysis';
        $.ajax({
            type : 'GET',
            url : url,
            data : {city:city},
            success : function(ret){
                if(ret.ret == 'succ'){
                    var myChart = echarts.init(document.getElementById('result_data'));
                    myChart.setOption(ret.option);
                }else{
                    alert('出错啦');
                }
            } ,
            dataType : 'json'
        });
        
        $.ajax({
            type : 'GET',
            url : '/index.php?r=job/citysalary',
            data : {city:city},
            success : function(ret){
                if(ret.ret == 'succ'){
                    var myChart = echarts.init(document.getElementById('result_max_salary'));
                    myChart.setOption(ret.option);
                }else{
                    alert('出错啦');
                }
            } ,
            dataType : 'json'
        });
                
    });
    
    $("#sub_search").click();
})

</script>