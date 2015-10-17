<?php

require_once ("http.php");

define ( 'Qiniu_RSF_EOF', 'EOF' );

/**
 * 1.
 * 首次请求 marker = ""
 * 2. 无论 err 值如何，均应该先看 items 是否有内容
 * 3. 如果后续没有更多数据，err 返回 EOF，markerOut 返回 ""（但不通过该特征来判断是否结束）
 */
function Qiniu_RSF_ListPrefix($self, $bucket, $prefix = '', $marker = '', $limit = 0) // => ($items,
                                                        // $markerOut, $err)
{
	global $QINIU_RSF_HOST;
	
	$query = array (
			'bucket' => $bucket 
	);
	if (! empty ( $prefix )) {
		$query ['prefix'] = $prefix;
	}
	if (! empty ( $marker )) {
		$query ['marker'] = $marker;
	}
	if (! empty ( $limit )) {
		$query ['limit'] = $limit;
	}
	
	$url = $QINIU_RSF_HOST . '/list?' . http_build_query ( $query );
	list ( $ret, $err ) = Qiniu_Client_Call ( $self, $url );
	if ($err !== null) {
		return array (
				null,
				'',
				$err 
		);
	}
	
	$items = $ret ['items'];
	if (empty ( $ret ['marker'] )) {
		$markerOut = '';
		$err = Qiniu_RSF_EOF;
	} else {
		$markerOut = $ret ['marker'];
	}
	return array (
			$items,
			$markerOut,
			$err 
	);
}

/**
 * 从指定URL抓取资源，并将该资源存储到指定空间中
 *
 * @param $url 指定的URL        	
 * @param $bucket 目标资源空间        	
 * @param $key 目标资源文件名        	
 *
 * @return array[] 包含已拉取的文件信息。
 *         成功时： [
 *         [
 *         "hash" => "<Hash string>",
 *         "key" => "<Key string>"
 *         ],
 *         null
 *         ]
 *        
 *         失败时： [
 *         null,
 *         Qiniu/Http/Error
 *         ]
 * @link http://developer.qiniu.com/docs/v6/api/reference/rs/fetch.html
 */
function Qiniu_Fetch($self,$url, $bucket, $key) {
	global $QINIU_FETCH_HOST;
	$resource = base64_urlSafeEncode ( $url );
	$to = entry ( $bucket, $key );
	$url = $QINIU_FETCH_HOST . '/fetch/' . $resource . '/to/' . $to;
	return Qiniu_Client_Call ( $self, $url );
}

/**
 * 计算七牛API中的数据格式
 *
 * @param $bucket 待操作的空间名        	
 * @param $key 待操作的文件名        	
 *
 * @return 符合七牛API规格的数据格式
 * @link http://developer.qiniu.com/docs/v6/api/reference/data-formats.html
 */
function entry($bucket, $key) {
	$en = $bucket;
	if (! empty ( $key )) {
		$en = $bucket . ':' . $key;
	}
	return base64_urlSafeEncode ( $en );
}


/**
 * 对提供的数据进行urlsafe的base64编码。
 *
 * @param string $data 待编码的数据，一般为字符串
 *
 * @return string 编码后的字符串
 * @link http://developer.qiniu.com/docs/v6/api/overview/appendix.html#urlsafe-base64
 */
function base64_urlSafeEncode($data)
{
	$find = array('+', '/');
	$replace = array('-', '_');
	return str_replace($find, $replace, base64_encode($data));
}