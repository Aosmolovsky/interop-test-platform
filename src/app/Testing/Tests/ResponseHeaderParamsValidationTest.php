<?php declare(strict_types=1);

namespace App\Testing\Tests;

use App\Http\Client\Response;
use App\Models\ApiSpec;
use App\Testing\TestCase;
use League\OpenAPIValidation\PSR7\ResponseAddress;
use League\OpenAPIValidation\PSR7\SpecFinder;
use League\OpenAPIValidation\PSR7\Validators\HeadersValidator;
use PHPUnit\Framework\AssertionFailedError;
use Throwable;

class ResponseHeaderParamsValidationTest extends TestCase
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ApiSpec
     */
    protected $apiSpec;

    /**
     * @var ResponseAddress
     */
    protected $operationAddress;

    /**
     * @var array
     */
    protected $specs = [];

    /**
     * @param Response $response
     * @param ApiSpec $apiSpec
     * @param ResponseAddress $operationAddress
     * @param array $specs
     */
    public function __construct(Response $response, ApiSpec $apiSpec, ResponseAddress $operationAddress, array $specs)
    {
        $this->response = $response;
        $this->apiSpec = $apiSpec;
        $this->operationAddress = $operationAddress;
        $this->specs = $specs;
    }

    /**
     * @return void
     */
    public function test()
    {
        $validator = new HeadersValidator(new SpecFinder($this->apiSpec->openapi));

        try {
            $validator->validate($this->operationAddress, $this->response->toPsrResponse());
        } catch (Throwable $e) {
            throw new AssertionFailedError($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return __('Response: :name API Scheme header params are specified correctly', ['name' => $this->apiSpec->name]);
    }

    /**
     * @return array
     */
    public function getActual(): array
    {
        return tap([], function (&$headers) {
            foreach (array_keys($this->getExpected()) as $name) {
                if ($header = $this->response->toPsrResponse()->getHeaderLine($name)) {
                    $headers[$name] = $header;
                }
            }
        });
    }

    /**
     * @return array
     */
    public function getExpected(): array
    {
        return tap([], function (&$specs) {
            foreach ($this->specs as $name => $spec) {
                $specs[$name] = $spec->getSerializableData();
            }
        });
    }
}
