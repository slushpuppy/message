<?php

namespace Http\Message\Decorator;

use Psr\Http\Message\StreamInterface;

/**
 * Decorates a stream.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
trait StreamDecorator
{
    /**
     * @var StreamInterface
     */
    protected $stream;

    /**
     * {@inheritdoc}
     */
    public function __toString() : string
    {
        return $this->stream->__toString();
    }

    /**
     * {@inheritdoc}
     */
    public function close() : void
    {
        $this->stream->close();
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        return $this->stream->detach();
    }

    /**
     * {@inheritdoc}
     */
    public function getSize() : ?int
    {
        return $this->stream->getSize();
    }

    /**
     * {@inheritdoc}
     */
    public function tell() : int
    {
        return $this->stream->tell();
    }

    /**
     * {@inheritdoc}
     */
    public function eof() : bool
    {
        return $this->stream->eof();
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable() : bool
    {
        return $this->stream->isSeekable();
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET) : void
    {
        $this->stream->seek($offset, $whence);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind() : void
    {
        $this->stream->rewind();
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable() : bool
    {
        return $this->stream->isWritable();
    }

    /**
     * {@inheritdoc}
     */
    public function write($string) : int
    {
        return $this->stream->write($string);
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable() : bool
    {
        return $this->stream->isReadable();
    }

    /**
     * {@inheritdoc}
     */
    public function read($length) : string
    {
        return $this->stream->read($length);
    }

    /**
     * {@inheritdoc}
     */
    public function getContents() : string
    {
        return $this->stream->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($key = null)
    {
        return $this->stream->getMetadata($key);
    }
}
