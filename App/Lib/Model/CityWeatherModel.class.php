<?php
/**
 * 城市天气model
 */
class CityWeatherModel extends Model
{
    protected $tableName        =   'city_weather';
    protected $_validate        = array(
        array('citycode','require','城市编码必须！'),
        array('today','require','日期必须！'),
        array('msg','require','消息必须！'),
    );
}
