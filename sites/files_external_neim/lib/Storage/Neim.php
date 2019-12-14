<?php
/**
 * @author Hemant Mann <hemant.mann121@gmail.com>
 *
 * @copyright Copyright (c) 2017, ownCloud GmbH.
 * @license GPL-2.0
 * 
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace OCA\Files_external_neim\Storage;

// use Kunnu\Dropbox\DropboxApp;
// use Kunnu\Dropbox\Dropbox as DropboxClient;
// use OCP\Files\Storage\FlysystemStorageAdapter;
// use OCP\Files\Storage;
use OCP\Files\Storage\IStorage;
use OCP\Lock\ILockingProvider;

// class Neim extends CacheableFlysystemAdapter {
// class Neim  {
class Neim extends \OC\Files\Storage\Common  {
    const APP_NAME = 'files_external_neim';

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var Adapter
     */
    protected $adapter;

    /**
     * This property is used to check whether the storage is case insensitive or not
     * @var boolean
     */
    protected $isCaseInsensitiveStorage = true;

    /**
     * @var Adapter
     */
    protected $flysystem;

    /**
     * Logger variable
     * @var \OCP\ILogger
     */
    protected $logger;

    protected $cacheFilemtime = [];

    /**
     * Initialize the storage backend with a flyssytem adapter
     * @override
     * @param \League\Flysystem\Filesystem $fs
     */
    public function setFlysystem222($fs) {
        // $this->flysystem = $fs;
        // $this->flysystem->addPlugin(new \League\Flysystem\Plugin\GetWithMetadata());
    }

    public function setAdapter222($adapter) {
        // $this->adapter = $adapter;
    }

    public function logit($mixed, $funcname = '', $line = 0) {
        $setlog = true;
        if (!$setlog) {return;}
        $file = '/tmp/neimoc.log';
        $stk = debug_backtrace()[1];
        // $funcname = $stk['function'];
        // $line = $stk['line'];
        $srcfile = substr($stk['file'], strlen('/home/gzleo/nextcloud/'));
        $srcfile= '';
        $allstk = json_encode($stk,  JSON_PRETTY_PRINT);
        $fullmsg = 'neim'.$srcfile.':'. $funcname.':'.$line.': '.json_encode($mixed, JSON_UNESCAPED_UNICODE)."\n";
        // $this->logger->warning();
        file_put_contents($file, $fullmsg, FILE_APPEND);
    }

    // ov means overlay
    private $ovroot = '';
    private $ovcache = '';
    private $ovmeta = '';
    private $ovtmp = '';
    /**
     * Dropbox constructor.
     * @throws \Exception
     */
    public function __construct($params) {
        $this->ovroot = '/home/blank/nextcloud/data/neimov/';
        $this->ovcache = $this->ovroot . 'cache/';
        $this->ovmeta = $this->ovroot . 'metajs/';
        $this->ovtmp = $this->ovroot . 'tmp/';
        $this->logit($params, __FUNCTION__, __LINE__);
        // $this->logit(\OC::$server, __LINE__);
        if (isset($params['user']) && isset($params['password'])
        ) {
        } else {
            throw new \Exception('Creating \OCA\Files_external_neim\Storage\Dropbox storage failed');
        }
        // $this->logger = \OC::$server->getLogger();
    }

	/**
	 * Get the identifier for the storage,
	 * the returned id should be the same for every storage object that is created with the same parameters
	 * and two storage objects with the same id should refer to two storages that display the same files.
	 *
	 * @return string
	 * @since 6.0.0
	 */
    public function getId() {
        // $this->logit($this->root, __FUNCTION__, __LINE__);
        return 'neim::' . 'host:port//';
    }
    private function getMetaFile($path) {
        $filepath = $this->ovroot.$path.'.metajs';
        return $filepath;
    }

	/**
	 * see http://php.net/manual/en/function.mkdir.php
	 * implementations need to implement a recursive mkdir
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function mkdir($path) {
        $this->logit($path, __FUNCTION__, __LINE__);
        $filepath = $this->ovroot . $path;
        $rv = mkdir($filepath);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.rmdir.php
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function rmdir($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        if ($path == '') {
            return true;
        }
        $filepath = $this->ovroot . $path;
        $rv = true;
        if (file_exists($filepath)) {
            $rv = rmdir($filepath);
        }
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.opendir.php
	 *
	 * @param string $path
	 * @return resource|false
	 * @since 6.0.0
	 */
    public function opendir($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $filepath = $this->ovroot . $path;
        $rv =  opendir($filepath);
        $this->logit($rv, __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.is-dir.php
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function is_dir($path){
        // $this->logit($path, __FUNCTION__, __LINE__);
        if ($path == "") {
            return true;
        }
        $dir = $this->ovroot . $path;
        $rv = is_dir($dir);
        // $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.is-file.php
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function is_file($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        if ($path == "") {
            return false;
        }
        $dir = $this->ovroot . $path;
        $rv = is_file($dir);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.stat.php
	 * only the following keys are required in the result: size and mtime
	 *
	 * @param string $path
	 * @return array|false
	 * @since 6.0.0
	 */
    public function stat($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $dir = $this->ovroot . $path;
        $rv = @stat($dir);
        if (!$rv) {
            $this->loadMetaData($path);
        }
        $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.filetype.php
	 *
	 * @param string $path
	 * @return string|false
	 * @since 6.0.0
	 */
    public function filetype($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $this->loadMetaData($path);
		try {
            $filepath = $this->ovroot . $path;
            if (is_dir($filepath)) {
				return 'dir';
			}else{
				return 'file';
			}
		} catch (\Exception $e) {

		}
		return false;
    }

	/**
	 * see http://php.net/manual/en/function.filesize.php
	 * The result for filesize when called on a folder is required to be 0
	 *
	 * @param string $path
	 * @return int|false
	 * @since 6.0.0
	 */
    public function filesize($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        if ($path == '') {
            return 4096;
        }
        $metax = $this->loadMetaData($path);
        if (isset($metax)) {
            $rv = $metax->size;
        }
        if (!isset($rv)) {
            $dir = $this->ovroot . $path;
            $rv = filesize($dir);
        }
        return $rv;
    }

	/**
	 * check if a file can be created in $path
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function isCreatable($path){
        // $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::isCreatable($path);
        // $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * check if a file can be read
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function isReadable($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $this->loadMetaData($path);
        $rv = parent::isReadable($path);
        $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * check if a file can be written to
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function isUpdatable($path){
        // $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::isUpdatable($path);
        // $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * check if a file can be deleted
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function isDeletable($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::isDeletable($path);
        if (strpos($path, 'ncsync/') === 0) {
            // $rv = false;
        }
        $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * check if a file can be shared
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function isSharable($path){
        // $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::isSharable($path);
        // $this->logit($rv, __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * get the full permissions of a path.
	 * Should return a combination of the PERMISSION_ constants defined in lib/public/constants.php
	 *
	 * @param string $path
	 * @return int
	 * @since 6.0.0
	 */
    public function getPermissions($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::getPermissions($path);
        $this->logit($rv, __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.file_exists.php
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function file_exists($path){
        // $this->logit($path, __FUNCTION__, __LINE__);
        if ($path == "") {
            return true;
        }
        $filepath = $this->ovroot . $path;
        $rv = file_exists($filepath);
        if (!$rv) {
            $this->loadMetaData($path);
            $rv = file_exists($this->getMetaFile($path));
        }
        $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.filemtime.php
	 *
	 * @param string $path
	 * @return int|false
	 * @since 6.0.0
	 */
    public function filemtime($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::filemtime($path);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.file_get_contents.php
	 *
	 * @param string $path
	 * @return string|false
	 * @since 6.0.0
	 */
    public function file_get_contents($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::file_get_contents($path);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.file_put_contents.php
	 *
	 * @param string $path
	 * @param string $data
	 * @return bool
	 * @since 6.0.0
	 */
    public function file_put_contents($path, $data){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::file_put_contents($path, $data);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.unlink.php
	 *
	 * @param string $path
	 * @return bool
	 * @since 6.0.0
	 */
    public function unlink($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        if ($path == '') {
            return true;
        }
        $filepath = $this->ovroot . $path;
        $rv = @unlink($filepath);
        $metafile = $filepath . '.metajs';
        @unlink($metafile);
        $this->logit($path.' '.$rv, __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.rename.php
	 *
	 * @param string $path1
	 * @param string $path2
	 * @return bool
	 * @since 6.0.0
	 */
    public function rename($path1, $path2){
        $this->logit($path1.' -> '.$path2, __FUNCTION__, __LINE__);
        $filepath1 = $this->ovroot . $path1;
        $filepath2 = $this->ovroot . $path2;
        if (is_dir($filepath1)) {
            $rv = rename($filepath1, $filepath2);
        }else{
            $filepath1 = $this->ovroot . $path1.'.metajs';
            $filepath2 = $this->ovroot . $path2.'.metajs';
            $rv = rename($filepath1, $filepath2);
        }
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.copy.php
	 *
	 * @param string $path1
	 * @param string $path2
	 * @return bool
	 * @since 6.0.0
	 */
    public function copy($path1, $path2){
        $this->logit($path1.' -> '.$path2, __FUNCTION__, __LINE__);
    }

	/**
	 * see http://php.net/manual/en/function.fopen.php
	 *
	 * @param string $path
	 * @param string $mode
	 * @return resource|false
	 * @since 6.0.0
	 */
    public function fopen($path, $mode){
        $this->logit($path.' '.$mode, __FUNCTION__, __LINE__);
        $isw = strpos($mode, 'w') > -1 || strpos($mode, 'c') > -1 || strpos($mode, 'a') > -1 || strpos($mode, 'x') > -1;
        $filepath = $this->ovroot . $path;
        $ctx = stream_context_create(array('neim' => array('session' => $path)));
        if ($isw) {
            $fp = fopen($filepath, $mode, false, $ctx);
        }else{
            $metax = $this->loadMetaData($path);
            if(isset($metax)) {
                $fp = fopen($metax->url, $mode, false, $ctx);
                if (!$fp) {
                    $this->logit([$path, $mode, $matax], __FUNCTION__, __LINE__);
                }
            }
        }
        $this->logit($path.' '.$mode.' '.$fp, __FUNCTION__, __LINE__);
        return $fp;
    }

	/**
	 * fallback implementation
	 *
	 * @param string $path
	 * @param resource $stream
	 * @param int $size
	 * @return int
	 */
	public function writeStream(string $path, $stream, int $size = null): int {
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::writeStream($path, $stream, $size);
        $this->logit([$path,$size,$rv], __FUNCTION__, __LINE__);
        $filepath = $this->ovroot . $path;
        if (file_exists($filepath)) {
            $size = $rv;
            $this->onwrclose($filepath, $size);
        }
        return $rv;
	}
    protected function onwrclose($path, $size) {
        $result = $this->_n163upfile2($path);
        if ($result->code == 200) {
            $metafile = $path . '.metajs';
            $result->path = substr($path, strlen($this->ovroot));
            $result->size = $size;
            $result->ctime = time();
            $result->imginfo = @getimagesize($path);
            $result->stat = stat($path);
            // $result->metadata = parent::getMetaData($result->path);
            $result->perm = parent::getPermissions($result->path);
            $result->owner = \OC_User::getUser();
            $result->etag = uniqid(); // see Common.php
            $result->mime = mime_content_type($path);
            $result->md5sum = md5_file($path);
            $this->putMetaData($result->path, $result);
            $metajs = json_encode($result);
            file_put_contents($metafile, $metajs);
            $this->savetometajs($path, $result, $metajs);
            unlink($path); // too early
        }
    }
    protected function savetometajs($path, $mjsobj, $mjsdata) {
        $dstdir = $this->ovmeta . '/' . substr($mjsobj->md5sum,0,2).'/'.substr($mjsobj->md5sum, 2,2);
        $dstfile = $dstdir . '/'.$mjsobj->md5sum;
        if (!file_exists($dstdir)) {
            mkdir($dstdir, 0744, true);
        }
        file_put_contents($dstfile, $mjsdata);
    }
    protected function metax2ocmeta($metax) {
        if (!$metax) {
            return null;
        }
		$data = [];
		$data['mimetype'] = $metax->mime;
		$data['mtime'] = $metax->ctime;
		if ($data['mtime'] === false) {
			$data['mtime'] = time();
		}
		if ($data['mimetype'] == 'httpd/unix-directory') {
			$data['size'] = -1; //unknown
		} else {
			$data['size'] = $metax->size;
		}
		$data['etag'] = $metax->etag;
		$data['storage_mtime'] = $data['mtime'];
		$data['permissions'] = $metax->perm;

		return $data;
    }

    function curlupfile2($requrl, $filepath, $mpname, $data, $headers) {
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create($filepath);
        } else { //
            $cFile = '@' . realpath($filepath);
        }
        $post = $data;
        $post[$mpname] = $cFile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $requrl);
        if (!empty($headers)) {
            $hdrlines = array();
            foreach ($headers as $k => $v) {
                $hdrlines[] = $k . ': ' . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $hdrlines);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        $result = curl_exec ($ch);
        $errno = curl_errno($ch);
        $errinfo = curl_error($ch);
        curl_close ($ch);
        if ($errno != 0) {
            // dvm(json_encode($errinfo).$cFile, __FUNCTION__ . ':'. __LINE__);
            $this->logger->warning(json_encode($errinfo).$cFile);
        }
        return $result;
    }

    function _n163upfile2($filepath) {
        $AppKey = ""; // TODO 后台配置
        $SecKey = "";
        // file format: return array('AppKey', 'SecKey');
        $seckeyfile = dirname(__FILE__).'/neimfs_seckeys.php';
        $keys = include($seckeyfile);
        $AppKey = $keys[0];
        $SecKey = $keys[1];
        // $AppKey = _neimfs_get_setting('appkey');
        // $Seckey = _neimfs_get_setting('seckey');

        // 注意ntp对时
        $ts = explode(' ',  microtime());
        // var_dump($ts);
        //$ts[1] = strval(intval($ts[1]) - 8*3600 + 1800);
        $chksum = ''; // SHA1(AppSecret + Nonce + CurTime), 16进制字符(String，小写)
        $chkstr = $SecKey.$ts[0].$ts[1];
        $chksum = sha1($chkstr);
        // echo $chkstr."\n";
        // echo $chksum."\n";
        // return ;
        $requrl = 'https://api.netease.im/nimserver/msg/fileUpload.action';
        $headers = array('AppKey'=>$AppKey, 'Nonce'=>$ts[0], 'CurTime'=>$ts[1], 'CheckSum'=>$chksum);
        $data = array('ishttps'=>'true', 'expireSec'=>86400*15, 'tag'=>'tmpftran');
        // var_dump($headers);
        // var_dump($data);
        $result = $this->curlupfile2($requrl, $filepath, 'content', $data, $headers);
        $result = json_decode($result);
        if ($result->code == 200) {
            $result->url = str_replace('nim-nosdn.netease.im', 'nim.nosdn.127.net', $result->url);
        }
        return $result;
    }

	/**
	 * get the mimetype for a file or folder
	 * The mimetype for a folder is required to be "httpd/unix-directory"
	 *
	 * @param string $path
	 * @return string|false
	 * @since 6.0.0
	 */
    public function getMimeType($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::getMimeType($path);
        $this->logit($rv, __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * see http://php.net/manual/en/function.hash-file.php
	 *
	 * @param string $type
	 * @param string $path
	 * @param bool $raw
	 * @return string|false
	 * @since 6.0.0
	 */
    public function hash($type, $path, $raw = false){
        $this->logit($type.' '.$path, __FUNCTION__, __LINE__);
    }

    /**
     * see http://php.net/manual/en/function.free_space.php
     *
     * @param string $path
     * @return int|false
     * @since 6.0.0
     */
    public function free_space($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::free_space($path);
        $vspace = 80*1024*1024*1024;
        $this->logit([$path, $rv, ' -> ', $vspace], __FUNCTION__, __LINE__);
        $rv = $vspace;
        return $rv;
    }

	/**
	 * search for occurrences of $query in file names
	 *
	 * @param string $query
	 * @return array|false
	 * @since 6.0.0
	 */
    public function search($query){
        $this->logit($query, __FUNCTION__, __LINE__);
    }

	/**
	 * see http://php.net/manual/en/function.touch.php
	 * If the backend does not support the operation, false should be returned
	 *
	 * @param string $path
	 * @param int $mtime
	 * @return bool
	 * @since 6.0.0
	 */
    public function touch($path, $mtime = null){
        $this->logit($path, __FUNCTION__, __LINE__);
    }

	/**
	 * get the path to a local version of the file.
	 * The local version of the file can be temporary and doesn't have to be persistent across requests
	 *
	 * @param string $path
	 * @return string|false
	 * @since 6.0.0
	 */
    public function getLocalFile($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::getLocalFile($path);
        $this->logit($rv, __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * check if a file or folder has been updated since $time
	 *
	 * @param string $path
	 * @param int $time
	 * @return bool
	 * @since 6.0.0
	 *
	 * hasUpdated for folders should return at least true if a file inside the folder is add, removed or renamed.
	 * returning true for other changes in the folder is optional
	 */
    public function hasUpdated($path, $time){
        $this->logit([$path,$time], __FUNCTION__, __LINE__);
        $rv = parent::hasUpdated($path, $time);
        $this->logit([$path, $time, $rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * get the ETag for a file or folder
	 *
	 * @param string $path
	 * @return string|false
	 * @since 6.0.0
	 */
    public function getETag($path){
        $this->logit($path, __FUNCTION__, __LINE__);
        $rv = parent::getETag($path);
        $this->logit([$path, $rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * Returns whether the storage is local, which means that files
	 * are stored on the local filesystem instead of remotely.
	 * Calling getLocalFile() for local storages should always
	 * return the local files, whereas for non-local storages
	 * it might return a temporary file.
	 *
	 * @return bool true if the files are stored locally, false otherwise
	 * @since 7.0.0
	 */
    public function isLocal(){
        // $this->logit('',__FUNCTION__, __LINE__);
        return false;
    }

	/**
	 * Check if the storage is an instance of $class or is a wrapper for a storage that is an instance of $class
	 *
	 * @param string $class
	 * @return bool
	 * @since 7.0.0
	 */
    public function instanceOfStorage333($class){
        $this->logit(['aaa',$class], __FUNCTION__, __LINE__);
        return parent::instanceOfStorage($class);
    }

    // the only place it does is when the storage was already marked as unavailable
    // 也就说返回直链基本啥用啊
	/**
	 * A custom storage implementation can return an url for direct download of a give file.
	 *
	 * For now the returned array can hold the parameter url - in future more attributes might follow.
	 *
	 * @param string $path
	 * @return array|false
	 * @since 8.0.0
	 */
    public function getDirectDownload($path){
        $this->logit(['aaa',$path], __FUNCTION__, __LINE__);
        $metax = $this->loadMetaData($path);
        $filepath = $this->ovroot.$path;
        if (isset($metax[$path])) {
            $rv = array('url'=>$metax[$path]->url);
        }
        if (!isset($rv)) {
            $rv = parent::getDirectDownload($path);
        }
        $this->logit(['aaa',$path, $rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * @param string $path the path of the target folder
	 * @param string $fileName the name of the file itself
	 * @return void
	 * @throws InvalidPathException
	 * @since 8.1.0
	 */
    public function verifyPath($path, $fileName){
        // $this->logit(['aaa',$path,$fileName], __FUNCTION__, __LINE__);
        $rv = parent::verifyPath($path, $fileName);
        // $this->logit(['aaa',$path,$fileName,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * @param IStorage $sourceStorage
	 * @param string $sourceInternalPath
	 * @param string $targetInternalPath
	 * @return bool
	 * @since 8.1.0
	 */
    public function copyFromStorage(IStorage $sourceStorage, $sourceInternalPath, $targetInternalPath){
        $this->logit(['aaa', $sourceInternalPath, $targetInternalPath], __FUNCTION__, __LINE__);
        $rv = parent::copyFromStorage($sourceStorage, $sourceInternalPath, $targetInternalPath);
        $this->logit(['aaa', $rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * @param IStorage $sourceStorage
	 * @param string $sourceInternalPath
	 * @param string $targetInternalPath
	 * @return bool
	 * @since 8.1.0
	 */
    public function moveFromStorage(IStorage $sourceStorage, $sourceInternalPath, $targetInternalPath){
        $this->logit(['aaa'], __FUNCTION__, __LINE__);
        $rv = parent::moveFromStorage($sourceStorage, $sourceInternalPath, $targetInternalPath);
        $this->logit(['aaa', $rv], __FUNCTION__, __LINE__);
        return $rv;
    }

	/**
	 * @param string $path The path of the file to acquire the lock for
	 * @param int $type \OCP\Lock\ILockingProvider::LOCK_SHARED or \OCP\Lock\ILockingProvider::LOCK_EXCLUSIVE
	 * @param \OCP\Lock\ILockingProvider $provider
	 * @throws \OCP\Lock\LockedException
	 * @since 8.1.0
	 */
    public function acquireLock($path, $type, ILockingProvider $provider){
        // $this->logit(['aaa',$path, $type], __FUNCTION__, __LINE__);
        return true;
    }

	/**
	 * @param string $path The path of the file to acquire the lock for
	 * @param int $type \OCP\Lock\ILockingProvider::LOCK_SHARED or \OCP\Lock\ILockingProvider::LOCK_EXCLUSIVE
	 * @param \OCP\Lock\ILockingProvider $provider
	 * @throws \OCP\Lock\LockedException
	 * @since 8.1.0
	 */
    public function releaseLock($path, $type, ILockingProvider $provider){
        // $this->logit(['aaa',$path, $type], __FUNCTION__, __LINE__);
        return true;
    }

	/**
	 * @param string $path The path of the file to change the lock for
	 * @param int $type \OCP\Lock\ILockingProvider::LOCK_SHARED or \OCP\Lock\ILockingProvider::LOCK_EXCLUSIVE
	 * @param \OCP\Lock\ILockingProvider $provider
	 * @throws \OCP\Lock\LockedException
	 * @since 8.1.0
	 */
    public function changeLock($path, $type, ILockingProvider $provider){
        // $this->logit(['aaa',$path, $type], __FUNCTION__, __LINE__);
        return true;
    }

	/**
	 * Test a storage for availability
	 *
	 * @since 8.2.0
	 * @return bool
	 */
    public function test(){
        $this->logit('aaa', __FUNCTION__, __LINE__);
        return true;
    }

	/**
	 * @since 8.2.0
	 * @return array [ available, last_checked ]
	 */
    public function getAvailability(){
        // $this->logit('aaa', __FUNCTION__, __LINE__);
        $rv = parent::getAvailability();
        // $this->logit(['aaa',$rv], __FUNCTION__, __LINE__);
        return $rv;
        // return array('available'=>true, 'last_checked'=>time());
    }

	/**
	 * @since 8.2.0
	 * @param bool $isAvailable
	 */
    public function setAvailability($isAvailable){
        // $this->logit(['aaa',$isAvailable], __FUNCTION__, __LINE__);
        parent::setAvailability($isAvailable);
    }

    public function needsPartFile(){
        $this->logit('aaa', __FUNCTION__, __LINE__);
        return false;
    }

    /**
     * @param string $path path for which to retrieve the owner
     * @since 9.0.0
     */
    public function getOwner($path) {
        // $this->logit('aaa', __FUNCTION__, __LINE__);
        $rv = parent::getOwner($path);
        // $this->logit($rv, __FUNCTION__, __LINE__);
        return $rv;
    }


    /**
     * @return ICache
     * @since 9.0.0
     */
    public function getCache333($path = '', $storage = NULL){
        $this->logit(['aaa',$path, $storage], __FUNCTION__, __LINE__);
        return parent::getCache($path, $storage);
        /*
          if (!isset($this->stc)) {
          $this->stc = new \OC\Files\Cache\Storage($this);
          $this->logit($this->stc, __FUNCTION__, __LINE__);
          $this->logit($this, __FUNCTION__, __LINE__);
          }
          return $this->stc;
        */
    }

    /**
     * @return ICache
     * @since 9.0.0
     */
    // var $stc = null;
    public function getStorageCache333(){
        $this->logit('aaa', __FUNCTION__, __LINE__);
        return parent::getStorageCache();
        /*
          if (!isset($this->stc)) {
          $this->stc = new \OC\Files\Cache\Storage($this);
          $this->logit($this->stc, __FUNCTION__, __LINE__);
          $this->logit($this, __FUNCTION__, __LINE__);
          }
        */
        return $this->stc;
    }

    /**
     * @return IPropagator
     * @since 9.0.0
     */
    public function getPropagator($storage = NULL){
        // $this->logit('aaa', __FUNCTION__, __LINE__);
        $rv = parent::getPropagator($storage);
        // $this->logit([$storage,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

    /**
     * @return IScanner
     * @since 9.0.0
     */
    public function getScanner($path = '', $storage = NULL){
        // $this->logit('aaa', __FUNCTION__, __LINE__);
        $rv = parent::getScanner($path, $storage);
        // $this->logit([$path, $storage,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

    /**
     * @return IUpdater
     * @since 9.0.0
     */
    public function getUpdater($storage = NULL) {
        // $this->logit('aaa', __FUNCTION__, __LINE__);
        $rv = parent::getUpdater($storage);
        // $this->logit([$storage,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

    /**
     * @return IWatcher
     * @since 9.0.0
     */
    public function getWatcher($path = '', $storage = NULL) {
        // $this->logit('aaa', __FUNCTION__, __LINE__);
        $rv = parent::getWatcher($path, $storage);
        // $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }

    public function getMetaData($path) {
        $this->logit(['aaa',$path], __FUNCTION__, __LINE__);
        $this->loadMetaData($path);
        $filepath = $this->ovroot . $path;
        if (file_exists($filepath)) {
            $rv = parent::getMetaData($path);
        }else{
            $rv = $this->metax2ocmeta($this->metajses[$path]);
        }
        $this->logit([$path,$rv], __FUNCTION__, __LINE__);
        return $rv;
    }
    // load from metajs
    protected $metajses = array(); // $path => metax , see onwrclose()
    protected function loadMetaData($path) {
        if (array_key_exists($path, $this->metajses)) {
            return $this->metajses[$path];
        }
        $filepath = $this->ovroot.$path;
        if (is_dir($filepath)) { return; }
        $metafile = $this->ovroot.$path.'.metajs';
        if (file_exists($metafile)) {
            $data = file_get_contents($metafile);
            $this->metajses[$path] = json_decode($data);
            return $this->metajses[$path];
        }else{
            $origok = file_exists($filepath);
            $origokstr = $origok  ? 'yes' : 'no';
            if ($origok) {
                $this->logit([$path, 'metajs error not exist', 'origin', $origokstr], __FUNCTION__, __LINE__);
            }
        }
        return false;
    }
    protected function putMetaData($path, $metajs) {
        $this->metajses[$path] = $metajs;
    }

    //

}
