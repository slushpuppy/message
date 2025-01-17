<?php

namespace spec\Http\Message\Decorator;

use Http\Message\Decorator\StreamDecorator;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\StreamInterface;

class StreamDecoratorSpec extends ObjectBehavior
{
    public function let(StreamInterface $stream)
    {
        $this->beAnInstanceOf('spec\Http\Message\Decorator\StreamDecoratorStub', [$stream]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('spec\Http\Message\Decorator\StreamDecoratorStub');
    }

    public function it_is_a_stream_decorator()
    {
        $this->shouldUseTrait('Http\Message\Decorator\StreamDecorator');
    }

    public function it_casts_the_stream_to_string(StreamInterface $stream)
    {
        $stream->__toString()->willReturn('body');

        $this->__toString()->shouldReturn('body');
    }

    public function it_closes_the_stream(StreamInterface $stream)
    {
        $stream->close()->shouldBeCalled();

        $this->close();
    }

    public function it_detaches_the_stream(StreamInterface $stream)
    {
        $stream->detach()->willReturn('detached');

        $this->detach()->shouldReturn('detached');
    }

    public function it_returns_the_size_of_the_stream(StreamInterface $stream)
    {
        $stream->getSize()->willReturn(1234);

        $this->getSize()->shouldReturn(1234);
    }

    public function it_returns_the_current_position_of_the_stream(StreamInterface $stream)
    {
        $stream->tell()->willReturn(0);

        $this->tell()->shouldReturn(0);
    }

    public function it_checks_whether_the_stream_is_eof(StreamInterface $stream)
    {
        $stream->eof()->willReturn(false);

        $this->eof()->shouldReturn(false);
    }

    public function it_checks_whether_the_stream_is_seekable(StreamInterface $stream)
    {
        $stream->isSeekable()->willReturn(true);

        $this->isSeekable()->shouldReturn(true);
    }

    public function it_seeks_the_current_position_of_the_stream(StreamInterface $stream)
    {
        $stream->seek(0, SEEK_SET)->shouldBeCalled();

        $this->seek(0);
    }

    public function it_rewinds_to_the_beginning_of_the_stream(StreamInterface $stream)
    {
        $stream->rewind()->shouldBeCalled();

        $this->rewind();
    }

    public function it_checks_whether_the_stream_is_writable(StreamInterface $stream)
    {
        $stream->isWritable()->willReturn(true);

        $this->isWritable()->shouldReturn(true);
    }

    public function it_writes_to_the_stream(StreamInterface $stream)
    {
        $stream->write('body')->shouldBeCalled();

        $this->write('body');
    }

    public function it_checks_whether_the_stream_is_readable(StreamInterface $stream)
    {
        $stream->isReadable()->willReturn(true);

        $this->isReadable()->shouldReturn(true);
    }

    public function it_reads_from_the_stream(StreamInterface $stream)
    {
        $stream->read(4)->willReturn('body');

        $this->read(4)->shouldReturn('body');
    }

    public function it_returns_the_contents_of_the_stream(StreamInterface $stream)
    {
        $stream->getContents()->willReturn('body');

        $this->getContents()->shouldReturn('body');
    }

    public function it_returns_metadata_of_the_stream(StreamInterface $stream)
    {
        $stream->getMetadata(null)->willReturn(['key' => 'value']);
        $stream->getMetadata('key')->willReturn('value');
        $stream->getMetadata('key2')->willReturn(null);

        $this->getMetadata()->shouldReturn(['key' => 'value']);
        $this->getMetadata('key')->shouldReturn('value');
        $this->getMetadata('key2')->shouldReturn(null);
    }

    public function getMatchers(): array
    {
        return [
            'useTrait' => function ($subject, $trait) {
                return class_uses($subject, $trait);
            },
        ];
    }
}

class StreamDecoratorStub implements StreamInterface
{
    use StreamDecorator;

    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }
}
