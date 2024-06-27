<?php

namespace Tests\Unit\Services\Auth;

use App\Services\Auth\JwtService;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Tests\TestCase;
use Mockery;

class JwtServiceTest extends TestCase
{
    protected $jwtService;
    protected $config;

    protected function setUp(): void
    {
        parent::setUp();

        $privateKey = InMemory::file(base_path('keys/private_key.pem'));
        $publicKey = InMemory::file(base_path('keys/public_key.pem'));

       // Create actual configuration instance
       $this->config = Configuration::forAsymmetricSigner(
        new Sha256(),
        $privateKey,
        $publicKey
    );

        // Create the JWT service with the actual configuration
        $this->jwtService = new JwtService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCreateToken()
    {
        $claims = ['sub' => '123', 'role' => 'admin'];
        $token = $this->jwtService->createToken($claims);
        
        $this->assertIsString($token);
    }

    public function testParseToken()
    {
        $claims = ['sub' => '123', 'role' => 'admin'];
        $token = $this->jwtService->createToken($claims);

        $parsedToken = $this->jwtService->parseToken($token);

        $this->assertInstanceOf(Token::class, $parsedToken);
        $this->assertEquals('123', $parsedToken->claims()->get('sub'));
        $this->assertEquals('admin', $parsedToken->claims()->get('claims')['role']);
    }

    public function testValidateToken()
    {
        $claims = ['sub' => '123', 'role' => 'admin'];
        $token = $this->jwtService->createToken($claims);

        $parsedToken = $this->jwtService->parseToken($token);
        $constraints = [new IssuedBy(config('app.url'))];

        $this->config->validator()->assert($parsedToken, ...$constraints);

        $this->assertTrue($this->jwtService->validateToken($parsedToken));
    }

    public function testValidateInvalidToken()
    {
        $privateKey = InMemory::plainText('-----BEGIN PRIVATE KEY-----...-----END PRIVATE KEY-----'); // replace with an actual private key
        $publicKey = InMemory::plainText('-----BEGIN PUBLIC KEY-----...-----END PUBLIC KEY-----'); // replace with an actual public key

        $invalidConfig = Configuration::forAsymmetricSigner(new Sha256(), $privateKey, $publicKey);

        $claims = ['sub' => '123', 'role' => 'admin', 'custom_claim' => 'custom_value'];
        $invalidToken = $this->jwtService->createToken($claims);

        $parsedToken = $invalidConfig->parser()->parse($invalidToken);

        $validator = $this->config->validator();
        $constraints = [new IssuedBy('http://invalid-url')]; // Invalid issuer URL

        $this->assertFalse($validator->validate($parsedToken, ...$constraints));
    }
}
