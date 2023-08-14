<?php

namespace HighWire\StreamWrapper;

/**
 * HighWire Stream Wrapper base.
 *
 * Provides an abstract generic stream wrapper for
 * wrapping around HTTP based streams using a guzzle client.
 */
abstract class StreamWrapperBase implements StreamWrapperInterface {
  /**
   * Stream wrapper bit flags that are the basis for composite types.
   *
   * Note that 0x0002 is skipped, because it was the value of a constant that
   * has since been removed.
   */

  /**
   * A filter that matches all wrappers.
   */
  const ALL = 0x0000;

  /**
  * Refers to a local file system location.
  */
  const LOCAL = 0x0001;

  /**
  * Wrapper is readable (almost always true).
  */
  const READ = 0x0004;

  /**
  * Wrapper is writeable.
  */
  const WRITE = 0x0008;

  /**
  * Exposed in the UI and potentially web accessible.
  */
  const VISIBLE = 0x0010;

  /**
  * Composite stream wrapper bit flags that are usually used as the types.
  */

  /**
  * Defines the stream wrapper bit flag for a hidden file.
  *
  * This is not visible in the UI or accessible via web, but readable and
  * writable; for instance, the temporary directory for file uploads.
  */
  const HIDDEN = 0x000C;

  /**
  * Hidden, readable and writeable using local files.
  */
  const LOCAL_HIDDEN = 0x000D;

  /**
  * Visible, readable and writeable.
  */
  const WRITE_VISIBLE = 0x001C;

  /**
  * Visible and read-only.
  */
  const READ_VISIBLE = 0x0014;

  /**
  * This is the default 'type' flag.
  *
  * This does not include StreamWrapperInterface::LOCAL, because PHP grants a
  * greater trust level to local files (for example, they can be used in an
  * "include" statement, regardless of the "allow_url_include" setting),
  * so stream wrappers need to explicitly opt-in to this.
  */
  const NORMAL = 0x001C;

  /**
  * Visible, readable and writeable using local files.
  */
  const LOCAL_NORMAL = 0x001D;

  /**
   * Instance URI (stream).
   *
   * A stream is referenced as "scheme://target".
   *
   * @var string
   */
  protected $uri;

  /**
   * The response from the backend service.
   *
   * @var \HighWire\Clients\HWResponse
   */
  protected $resp;

  /**
   * The response body from the backend service.
   *
   * @var \GuzzleHttp\Psr7\Stream
   */
  protected $body;

  /**
   * The service client.
   *
   * @var \HighWire\Clients\Client
   */
  protected $client;

  /**
   * Define the scheme for this stream wrapper.
   *
   * This should be overriden by the implementing class.
   */
  const SCHEME = '';

  /**
   * Get the service client.
   *
   * This should be overriden by the implementing class. Note that there
   * is no dependancy injection for php stream wrappers. For testing, extend
   * this class and override this method.
   *
   * @return \HighWire\Clients\Client
   *   The service client
   */
  abstract protected function client();

  /**
   * Given a URI, GET the response from the client.
   *
   * This should be overriden by the implementing class.
   *
   * @param string $uri
   *   The stream-wrapper URI for the resource.
   *
   * @return \HighWire\Clients\HWResponseInterface
   *   The response from the backend service.
   */
  abstract protected function getResponse($uri);

  /**
   * Given a URI, HEAD the response from the client.
   *
   * This should be overriden by the implementing class.
   *
   * @param string $uri
   *   The stream-wrapper URI for the resource.
   *
   * @return \Psr\Http\Message\ResponseInterface
   *   The response from the backend service.
   */
  abstract protected function headResponse($uri);

  /**
   * Default options for a stat.
   *
   * @var array
   *
   * @see https://github.com/guzzle/psr7/blob/master/src/StreamWrapper.php
   */
  protected $stat = [
    // device number
    'dev' => 0,
    // inode number
    'ino' => 0,
    // inode protection (regular file + read only)
    'mode' => 0100000 | 0444,
    // number of links
    'nlink' => 0,
     // userid of owner
    'uid' => 0,
    // groupid of owner
    'gid' => 0,
    // device type, if inode device *
    'rdev' => 0,
    // size in bytes
    'size' => 0,
     // time of last access (Unix timestamp)
    'atime' => 0,
    // time of last modification (Unix timestamp)
    'mtime' => 0,
    // time of last inode change (Unix timestamp)
    'ctime' => 0,
    // blocksize of filesystem IO
    'blksize' => 0,
    // number of blocks allocated
    'blocks' => 0,
  ];

  /**
   * {@inheritdoc}
   */
  public function setUri($uri) {
    $this->uri = $uri;
  }

  /**
   * {@inheritdoc}
   */
  public function getUri() {
    return $this->uri;
  }

  /**
   * {@inheritdoc}
   */
  public static function getType() {
    return self::READ & self::HIDDEN;
  }

  /**
   * {@inheritdoc}
   */
  public function realpath() {
    return $this->uri;
  }

  /**
   * {@inheritdoc}
   */
  public function dirname($uri = NULL) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_close() {
    $this->body->close();
  }

  /**
   * {@inheritdoc}
   */
  public function stream_eof() {
    return $this->body->eof();
  }

  /**
   * {@inheritdoc}
   */
  public function stream_lock($operation) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_open($path, $mode, $options, &$opened_path) {
    if (!in_array($mode, ['r', 'rb', 'rt'])) {
      if ($options & STREAM_REPORT_ERRORS) {
        trigger_error('stream_open() write modes not supported for HTTP stream wrappers', E_USER_WARNING);
      }
      return FALSE;
    }

    try {
      $this->setUri($path);
      $this->resp = $this->getResponse($path);
      $this->body = $this->resp->getData();
    }
    catch (\Exception $e) {
      return FALSE;
    }

    if ($options & STREAM_USE_PATH) {
      $opened_path = $path;
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_read($count) {
    return $this->body->read($count);
  }

  /**
   * {@inheritdoc}
   */
  public function stream_seek($offset, $whence = SEEK_SET) {
    try {
      $this->body->seek($offset, $whence);
    }
    catch (\RuntimeException $e) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Change stream options.
   *
   * This method is called to set options on the stream.
   *
   * @param int $option
   *   One of:
   *   - STREAM_OPTION_BLOCKING: The method was called in response to
   *     stream_set_blocking().
   *   - STREAM_OPTION_READ_TIMEOUT: The method was called in response to
   *     stream_set_timeout().
   *   - STREAM_OPTION_WRITE_BUFFER: The method was called in response to
   *     stream_set_write_buffer().
   * @param int $arg1
   *   If option is:
   *   - STREAM_OPTION_BLOCKING: The requested blocking mode:
   *     - 1 means blocking.
   *     - 0 means not blocking.
   *   - STREAM_OPTION_READ_TIMEOUT: The timeout in seconds.
   *   - STREAM_OPTION_WRITE_BUFFER: The buffer mode, STREAM_BUFFER_NONE or
   *     STREAM_BUFFER_FULL.
   * @param int $arg2
   *   If option is:
   *   - STREAM_OPTION_BLOCKING: This option is not set.
   *   - STREAM_OPTION_READ_TIMEOUT: The timeout in microseconds.
   *   - STREAM_OPTION_WRITE_BUFFER: The requested buffer size.
   *
   * @return bool
   *   TRUE on success, FALSE otherwise. If $option is not implemented, FALSE
   *   should be returned.
   */
  public function stream_set_option($option, $arg1, $arg2) {
    if ($option == STREAM_OPTION_READ_TIMEOUT) {
      // $timeout = $arg1 + ($arg2 / 1000000);
      // TODO: Set the timeout on the client.
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_stat() {
    $stat = $this->stat;

    if (!empty($this->resp)) {
      if ($this->resp->hasHeader('Content-Length')) {
        $stat['size'] = (int) $this->resp->getHeaderLine('Content-Length');
      }
      if ($this->resp->hasHeader('Last-Modified')) {
        if ($mtime = strtotime($this->resp->getHeaderLine('Last-Modified'))) {
          $stat['mtime'] = $mtime;
        }
      }
    }

    return $stat;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_tell() {
    return $this->body->tell();
  }

  /**
   * {@inheritdoc}
   */
  public function url_stat($path, $flags) {
    $stat = $this->stat;

    try {
      $this->setUri($path);
      $resp = $this->headResponse($path);
      if ($resp->hasHeader('Content-Length')) {
        $stat['size'] = (int) $resp->getHeaderLine('Content-Length');
      }
      if ($resp->hasHeader('Last-Modified')) {
        if ($mtime = strtotime($resp->getHeaderLine('Last-Modified'))) {
          $stat['mtime'] = $mtime;
        }
      }
      return $stat;
    }
    catch (\Exception $e) {
      if ($flags & STREAM_URL_STAT_QUIET) {
        return $stat;
      }
      else {
        trigger_error($e->getMessage());
        return $stat;
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function dir_closedir() {
    trigger_error('dir_closedir() not supported by stream wrapper', E_USER_WARNING);
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function dir_opendir($path, $options) {
    trigger_error('dir_opendir() not supported by stream wrapper', E_USER_WARNING);
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function dir_readdir() {
    trigger_error('dir_readdir() not supported by stream wrapper', E_USER_WARNING);
    return FALSE;
  }

  /**
   * {@inheritdoc}
   *
   * Not Supported.
   */
  public function stream_cast($cast_as) {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function dir_rewinddir() {
    trigger_error('dir_rewinddir() not supported for read only stream wrappers', E_USER_WARNING);
    return FALSE;
  }

  /**
   * Support for fwrite(), file_put_contents() etc.
   *
   * Data will not be written as this is a read-only stream wrapper.
   *
   * @param string $data
   *   The string to be written.
   *
   * @return int
   *   0 as data will not be written.
   *
   * @see http://php.net/manual/streamwrapper.stream-write.php
   */
  public function stream_write($data) {
    trigger_error('stream_write() not supported for read-only stream wrappers', E_USER_WARNING);
    return 0;
  }

  /**
   * Support for fflush().
   *
   * Nothing will be output to the file, as this is a read-only stream wrapper.
   * However as stream_flush is called during stream_close we should not trigger
   * an error.
   *
   * @return bool
   *   FALSE, as no data will be stored.
   *
   * @see http://php.net/manual/streamwrapper.stream-flush.php
   */
  public function stream_flush() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   *
   * Does not change meta data as this is a read-only stream wrapper.
   */
  public function stream_metadata($uri, $option, $value) {
    trigger_error('stream_metadata() not supported for read-only stream wrappers', E_USER_WARNING);
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function stream_truncate($new_size) {
    trigger_error('stream_truncate() not supported for read-only stream wrappers', E_USER_WARNING);
    return FALSE;
  }

  /**
   * Support for unlink().
   *
   * The file will not be deleted from the stream as this is a read-only stream
   * wrapper.
   *
   * @param string $uri
   *   A string containing the uri to the resource to delete.
   *
   * @return bool
   *   TRUE so that file_delete() will remove db reference to file. File is not
   *   actually deleted.
   *
   * @see http://php.net/manual/streamwrapper.unlink.php
   */
  public function unlink($uri) {
    trigger_error('unlink() not supported for read-only stream wrappers', E_USER_WARNING);
    return TRUE;
  }

  /**
   * Support for rename().
   *
   * This file will not be renamed as this is a read-only stream wrapper.
   *
   * @param string $from_uri
   *   The uri to the file to rename.
   * @param string $to_uri
   *   The new uri for file.
   *
   * @return bool
   *   FALSE as file will never be renamed.
   *
   * @see http://php.net/manual/streamwrapper.rename.php
   */
  public function rename($from_uri, $to_uri) {
    trigger_error('rename() not supported for read-only stream wrappers', E_USER_WARNING);
    return FALSE;
  }

  /**
   * Support for mkdir().
   *
   * Directory will never be created as this is a read-only stream wrapper.
   *
   * @param string $uri
   *   A string containing the URI to the directory to create.
   * @param int $mode
   *   Permission flags - see mkdir().
   * @param int $options
   *   A bit mask of STREAM_REPORT_ERRORS and STREAM_MKDIR_RECURSIVE.
   *
   * @return bool
   *   FALSE as directory will never be created.
   *
   * @see http://php.net/manual/streamwrapper.mkdir.php
   */
  public function mkdir($uri, $mode, $options) {
    trigger_error('mkdir() not supported for read-only stream wrappers', E_USER_WARNING);
    return FALSE;
  }

  /**
   * Support for rmdir().
   *
   * Directory will never be deleted as this is a read-only stream wrapper.
   *
   * @param string $uri
   *   A string containing the URI to the directory to delete.
   * @param int $options
   *   A bit mask of STREAM_REPORT_ERRORS.
   *
   * @return bool
   *   FALSE as directory will never be deleted.
   *
   * @see http://php.net/manual/streamwrapper.rmdir.php
   */
  public function rmdir($uri, $options) {
    trigger_error('rmdir() not supported for read-only stream wrappers', E_USER_WARNING);
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function registerStreamWrapper() {
    if (empty(static::SCHEME)) {
      throw new \Exception('Class variable $scheme not defined in ' . get_called_class());
    }
    stream_wrapper_register(static::SCHEME, get_called_class(), STREAM_IS_URL);
  }

}
