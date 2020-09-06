<?php

namespace EllGreen\Pace\Tests\Sitemap;

use EllGreen\Pace\Sitemap\Page;
use EllGreen\Pace\Structure;
use EllGreen\Pace\Tests\TestCase;
use EllGreen\Pace\View\Helpers\Page as PageHelper;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Filesystem\Filesystem;
use Mni\FrontYAML\Document;
use Mni\FrontYAML\Parser;
use Mockery\MockInterface;

class PageTest extends TestCase
{
    private Page $page;
    private MockInterface $factory;
    private MockInterface $parser;
    private MockInterface $filesystem;

    public function setUp(): void
    {
        $this->page = new Page(
            new Structure('/root'),
            $this->factory = $this->mock(Factory::class),
            $this->parser = $this->mock(Parser::class),
            $this->filesystem = $this->mock(Filesystem::class)
        );
    }

    public function testPathNotSet()
    {
        $this->expectException(Exception::class);

        $this->page->path();
    }

    public function testPath()
    {
        $this->page->setPath($path = '/root/path/to/view');

        $this->assertSame($path, $this->page->path()->__toString());
    }

    /**
     * @dataProvider pathProvider
     */
    public function testViewName($path, $expected)
    {
        $this->page->setPath($path);

        $this->assertSame($expected['view'], $this->page->viewName());
    }

    /**
     * @dataProvider pathProvider
     */
    public function testOutputPath($path, $expected)
    {
        $this->page->setPath($path);

        $expectedOutput = "/root/build/{$expected['output']}";
        $this->assertSame($expectedOutput, $this->page->outputPath());
    }

    /**
     * @dataProvider pathProvider
     */
    public function testIsMarkdown($path, $expected)
    {
        $this->page->setPath($path);

        $this->assertSame($expected['markdown'] ?? false, $this->page->isMarkdown());
    }

    public function testRenderBlade()
    {
        $this->page->setPath('/root/resources/views/pages/index.blade.php');

        $this->factory->shouldReceive('make')->once()->with('pages.index', [
            'page' => new PageHelper($this->page),
        ])->andReturn('<p>Test</p>');

        $this->page->render();

        $this->expectException(Exception::class);

        $this->page->metadata();
    }

    public function testRenderMarkdown()
    {
        $this->page->setPath($path = '/root/resources/views/pages/index.md');

        $this->filesystem->shouldReceive('get')->with($path)->andReturn($markdown = '# Markdown');
        $this->parser->shouldReceive('parse')->with($markdown)->andReturn(
            $document = $this->mock(Document::class)
        );

        $document->shouldReceive([
            'getYAML' => [],
            'getContent' => $markdown,
        ]);

        $this->assertSame($markdown, $this->page->render());
    }

    public function testRenderMarkdownExtends()
    {
        $this->page->setPath($path = '/root/resources/views/pages/index.md');

        $this->filesystem->shouldReceive('get')->with($path)->andReturn($markdown = '# Markdown');
        $this->parser->shouldReceive('parse')->with($markdown)->andReturn(
            $document = $this->mock(Document::class)
        );

        $document->shouldReceive([
            'getYAML' => ['extends' => $view = 'components.layout.markdown'],
            'getContent' => $markdown,
        ]);

        $this->factory->shouldReceive('make')->once()->with($view, [
            'markdown' => $markdown,
            'page' => new PageHelper($this->page),
        ])->andReturn($html = '<p>Test</p>');

        $this->assertSame($html, $this->page->render());
    }

    public function pathProvider()
    {
        $path = "/root/resources/views/pages/";

        return [
            [
                $path.'index.blade.php',
                [
                    'view' => 'pages.index',
                    'output' => 'index.html',
                ],
            ],
            [
                $path.'about.blade.php',
                [
                    'view' => 'pages.about',
                    'output' => 'about/index.html',
                ],
            ],
            [
                $path.'about/index.blade.php',
                [
                    'view' => 'pages.about.index',
                    'output' => 'about/index.html',
                ],
            ],
            [
                $path.'posts/2020/post.blade.php',
                [
                    'view' => 'pages.posts.2020.post',
                    'output' => 'posts/2020/post/index.html',
                ],
            ],
            [
                $path.'posts/post.md',
                [
                    'view' => null,
                    'output' => 'posts/post/index.html',
                    'markdown' => true,
                ],
            ],
        ];
    }
}
