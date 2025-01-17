<?php

namespace spec\Http\Message\Formatter;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class CurlCommandFormatterSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Http\Message\Formatter\CurlCommandFormatter');
    }

    public function it_is_a_formatter()
    {
        $this->shouldImplement('Http\Message\Formatter');
    }

    public function it_formats_the_request(RequestInterface $request, UriInterface $uri, StreamInterface $body)
    {
        $request->getUri()->willReturn($uri);
        $request->getBody()->willReturn($body);

        $uri->withFragment('')->shouldBeCalled()->willReturn('http://foo.com/bar');
        $request->getMethod()->willReturn('GET');
        $request->getProtocolVersion()->willReturn('1.1');

        $request->getHeaders()->willReturn(['foo' => ['bar', 'baz']]);
        $request->getHeaderLine('foo')->willReturn('bar, baz');

        $this->formatRequest($request)->shouldReturn('curl \'http://foo.com/bar\' -H \'foo: bar, baz\'');
    }

    public function it_formats_post_request(RequestInterface $request, UriInterface $uri, StreamInterface $body)
    {
        $request->getUri()->willReturn($uri);
        $request->getBody()->willReturn($body);

        $body->__toString()->willReturn('body " data'." test' bar");
        $body->getSize()->willReturn(1);
        $body->isSeekable()->willReturn(true);
        $body->rewind()->willReturn(true);

        $uri->withFragment('')->shouldBeCalled()->willReturn('http://foo.com/bar');
        $request->getMethod()->willReturn('POST');
        $request->getProtocolVersion()->willReturn('2.0');

        $request->getHeaders()->willReturn([]);

        $this->formatRequest($request)->shouldReturn("curl 'http://foo.com/bar' --http2 --request POST --data 'body \" data test'\'' bar'");
    }

    public function it_does_nothing_for_response(ResponseInterface $response, RequestInterface $request)
    {
        $this->formatResponse($response)->shouldReturn('');
        $this->formatResponseForRequest($response, $request)->shouldReturn('');
    }

    public function it_formats_the_request_with_user_agent(RequestInterface $request, UriInterface $uri, StreamInterface $body)
    {
        $request->getUri()->willReturn($uri);
        $request->getBody()->willReturn($body);

        $uri->withFragment('')->shouldBeCalled()->willReturn('http://foo.com/bar');
        $request->getMethod()->willReturn('GET');
        $request->getProtocolVersion()->willReturn('1.1');
        $uri->withFragment('')->shouldBeCalled()->willReturn('http://foo.com/bar');
        $request->getHeaders()->willReturn(['user-agent' => ['foobar-browser']]);

        $this->formatRequest($request)->shouldReturn("curl 'http://foo.com/bar' -A 'foobar-browser'");
    }

    public function it_formats_requests_with_null_bytes(RequestInterface $request, UriInterface $uri, StreamInterface $body)
    {
        $request->getUri()->willReturn($uri);
        $request->getBody()->willReturn($body);

        $body->__toString()->willReturn("\0");
        $body->getSize()->willReturn(1);
        $body->isSeekable()->willReturn(true);
        $body->rewind()->willReturn(true);

        $uri->withFragment('')->willReturn('http://foo.com/bar');
        $request->getMethod()->willReturn('POST');
        $request->getProtocolVersion()->willReturn('1.1');
        $request->getHeaders()->willReturn([]);

        $this->formatRequest($request)->shouldReturn("curl 'http://foo.com/bar' --request POST --data '[binary stream omitted]'");
    }

    public function it_formats_requests_with_line_break(RequestInterface $request, UriInterface $uri, StreamInterface $body)
    {
        $request->getUri()->willReturn($uri);
        $request->getBody()->willReturn($body);

        $body->__toString()->willReturn("foo\nbar");
        $body->getSize()->willReturn(1);
        $body->isSeekable()->willReturn(true);
        $body->rewind()->willReturn(true);

        $uri->withFragment('')->willReturn('http://foo.com/bar');
        $request->getMethod()->willReturn('POST');
        $request->getProtocolVersion()->willReturn('1.1');
        $request->getHeaders()->willReturn([]);

        $this->formatRequest($request)->shouldReturn("curl 'http://foo.com/bar' --request POST --data 'foo\nbar'");
    }

    public function it_formats_requests_with_nonseekable_body(RequestInterface $request, UriInterface $uri, StreamInterface $body)
    {
        $request->getUri()->willReturn($uri);
        $request->getBody()->willReturn($body);

        $body->getSize()->willReturn(1);
        $body->isSeekable()->willReturn(false);
        $body->__toString()->shouldNotBeCalled();
        $body->rewind()->shouldNotBeCalled();

        $uri->withFragment('')->willReturn('http://foo.com/bar');
        $request->getMethod()->willReturn('POST');
        $request->getProtocolVersion()->willReturn('1.1');
        $request->getHeaders()->willReturn([]);

        $this->formatRequest($request)->shouldReturn("curl 'http://foo.com/bar' --request POST --data '[non-seekable stream omitted]'");
    }

    public function it_formats_requests_with_long_body(RequestInterface $request, UriInterface $uri, StreamInterface $body)
    {
        $request->getUri()->willReturn($uri);
        $request->getBody()->willReturn($body);

        $body->__toString()->willReturn('a very long body');
        $body->getSize()->willReturn(2097153);
        $body->isSeekable()->willReturn(true);
        $body->rewind()->willReturn(true);

        $uri->withFragment('')->willReturn('http://foo.com/bar');
        $request->getMethod()->willReturn('POST');
        $request->getProtocolVersion()->willReturn('1.1');
        $request->getHeaders()->willReturn([]);

        $this->formatRequest($request)->shouldReturn("curl 'http://foo.com/bar' --request POST --data '[too long stream omitted]'");
    }
}
