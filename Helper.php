<?php

/**
 * Class Helper 工具类
 *
 * author Turnover <hehan123456@qq.com>
 */
class Helper
{
    /**
     * 格式化ID
     *
     * @param $idList
     * @param string $key
     * @return array
     */
    public function getIdList($idList,$key = 'id'){
        $list = array();
        foreach ($idList as $id) {
            $list[] = $id[$key];
        }
        return $list;
    }

    /**
     * 根据指定字段合并数组
     *
     * @param $arr1
     * @param $arr2
     * @param string $key 根据字段合并
     * @return array
     */
    public function mergeById(&$arr1,&$arr2,$key = 'id'){
        $mergeArr = array();
        $keyArr = explode(',',$key);
        $count = count($keyArr);
        if ($count == 1) {
            foreach($arr1 as $value) $mergeArr[$value[$key]] = $value;
            foreach($arr2 as $value) {
                $mergeArr[$value[$key]] = isset($mergeArr[$value[$key]]) ? $mergeArr[$value[$key]] + $value : $value;
            }
        } else {
            foreach($arr1 as $value) {
                $key = '';
                foreach ($keyArr as $v) {
                    $key.= $value[$v].'-';
                }
                $key = rtrim($key,'-');
                $mergeArr[$key] = $value;
            }
            foreach($arr2 as $value) {
                $key = '';
                foreach ($keyArr as $v) {
                    $key.= $value[$v].'-';
                }
                $key = rtrim($key,'-');
                $mergeArr[$key] = isset($mergeArr[$key]) ? $mergeArr[$key] + $value : $value;
            }
        }
        return $mergeArr;
    }

    /**
     * 无限极分类树状结构
     *
     * @param array $list 无限极分类数据
     * @param string $parentField 父级字段
     * @param string $childField 子级字段
     * @param int $pid 父ID
     * @return array
     */
    public function getTree($list = array(), $parentField = '', $childField = '',$pid = 0)
    {
        $tree = [];
        if (!empty($list)) {
            //先修改为以id为下标的列表
            $newList = [];
            foreach ($list as $k => $v) {
                $newList[$v[$parentField]] = $v;
            }
            //然后开始组装成特殊格式
            foreach ($newList as $value) {
                if ($pid == $value[$childField]) {//先取出顶级
                    $tree[] = &$newList[$value[$parentField]];
                } elseif (isset($newList[$value[$childField]])) {
                    //再判定非顶级的pid是否存在，如果存在，则再pid所在的数组下面加入一个字段items，来将本身存进去
                    $newList[$value[$childField]]['child'][] = &$newList[$value[$parentField]];
                }
            }
        }
        return $tree;
    }

    /**
     * 获取目录下所有文件列表
     *
     * @param string $dir 目录
     * @return array 文件列表
     */
    public function getFileNameList ($dir = '') {
        $dir = opendir($dir);
        if (false != $dir) {
            $i=0;
            $dirArray = [];
            while ( false !== ($file = readdir ( $dir )) ) {
                //去掉"“.”、“..”
                if ($file != "." && $file != "..") {
                    $dirArray[$i]=$file;
                    $i++;
                }
            }
            //关闭句柄
            closedir ( $dir );
        }
        return $dirArray;
    }
}