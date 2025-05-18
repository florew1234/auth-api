<?php

namespace Tests;

use App\Controllers\AuthController;
use App\Models\User;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $controller;

    protected function setUp(): void
    {
        $this->controller = new AuthController();
        http_response_code(200);
        // wrapper personalisé pour la méthode Register 
        stream_wrapper_unregister('php');
        stream_wrapper_register('php', PhpInputStream::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        stream_wrapper_restore('php');
    }

    private function setInputStream(array $data): void
    {
        PhpInputStream::$input = json_encode($data);
    }

    public function testRegisterSuccess()
    {
        
        $data = [
            'first_name' => 'Alice',
            'last_name' => 'Dupont',
            'email' => 'alice@example.com',
            'password' => 'secret123'
        ];
        $this->setInputStream($data);

        $userMock = Mockery::mock('alias:' . User::class);
        $userMock->shouldReceive('getByEmail')
            ->with('alice@example.com')
            ->andReturn(null)
            ->once();
        $userMock->shouldReceive('create')
            ->with('Alice', 'Dupont', 'alice@example.com', 'secret123')
            ->once();

        
        ob_start();
        $this->controller->register();
        $output = ob_get_clean();

        
        $this->assertEquals(200, http_response_code(), "Output: $output");
        $this->assertEquals(json_encode(['message' => 'User created successfully']), $output);
    }
}

// wrapper personalisé pour simuler les données dans php://input
class PhpInputStream
{
    public static $input = '';
    private $position = 0;

    public function stream_open($path, $mode, $options, &$opened_path)
    {
        return true;
    }

    public function stream_read($count)
    {
        $chunk = substr(self::$input, $this->position, $count);
        $this->position += strlen($chunk);
        return $chunk;
    }

    public function stream_eof()
    {
        return $this->position >= strlen(self::$input);
    }

    public function stream_stat()
    {
        return [];
    }
}