<?php


use Makasim\File\TempFile;
use PHPUnit\Framework\TestCase;

class TempFileTest extends TestCase
{
    protected function tearDown(): void
    {
        // Xóa tất cả các file tạm sau mỗi test
        foreach (TempFile::getTempFiles() as $tempFile) {
            if (file_exists($tempFile)) {
                @unlink($tempFile);
            }
        }
    }

    public function testConstruct()
    {
        $tempFile = new TempFile('testfile.txt');
        $this->assertInstanceOf(TempFile::class, $tempFile);
        $this->assertTrue(file_exists('testfile.txt'));
    }

    public function testPersist()
    {
        $tempFile = new TempFile('testfile.txt');
        $result = $tempFile->persist();
        $this->assertSame($tempFile, $result);
        $this->assertFalse(isset(TempFile::getTempFiles()['testfile.txt']));
    }

    public function testGenerate()
    {
        $tempFile = TempFile::generate('php-test-');
        $this->assertInstanceOf(TempFile::class, $tempFile);
        $this->assertTrue(file_exists($tempFile->getPathname()));
    }

    public function testFrom()
    {
        file_put_contents('original.txt', 'Hello World');
        $tempFile = TempFile::from('original.txt', 'php-copy-');
        
        $this->assertInstanceOf(TempFile::class, $tempFile);
        $this->assertTrue(file_exists($tempFile->getPathname()));
        
        // Kiểm tra nội dung
        $this->assertEquals('Hello World', file_get_contents($tempFile->getPathname()));
        
        // Xóa file gốc
        unlink('original.txt');
    }
}