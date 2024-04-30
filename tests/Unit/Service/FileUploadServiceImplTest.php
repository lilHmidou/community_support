<?php

namespace App\Tests\Unit\Service;

use App\Service\FileUploadService\FileUploadServiceImpl;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use PHPUnit\Framework\TestCase;

class FileUploadServiceImplTest extends TestCase
{
    private FileUploadServiceImpl $service;
    private $parameterBagMock;

    protected function setUp(): void
    {
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $this->parameterBagMock->method('get')
            ->with('docs_directory')
            ->willReturn('/fake/directory');

        $this->service = new FileUploadServiceImpl($this->parameterBagMock);
    }

    public function testUploadFile(): void
    {
        $fileMock = $this->createMock(UploadedFile::class);
        $fileMock->method('getClientOriginalName')
            ->willReturn('test file.jpg');
        $fileMock->method('guessExtension')
            ->willReturn('jpg');
        $fileMock->expects($this->once())
            ->method('move')
            ->with('/fake/directory', $this->callback(function($filename) {
                return preg_match('/testfile-[a-z0-9]+\.jpg/', $filename) === 1;
            }));

        $result = $this->service->uploadFile($fileMock);
        $this->assertMatchesRegularExpression('/testfile-[a-z0-9]+\.jpg/', $result);
    }

}
