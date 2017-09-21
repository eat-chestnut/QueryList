<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 2017/9/21
 */

namespace QL;

use QL\Contracts\ServiceProviderContract;
use QL\Exceptions\ServiceNotFoundException;
use QL\Providers\EncodeServiceProvider;
use Closure;

class Kernel
{
    protected $providers = [
        EncodeServiceProvider::class
    ];

    protected $binds;
    protected $ql;

    /**
     * Kernel constructor.
     * @param $ql
     */
    public function __construct(QueryList $ql)
    {
        $this->ql = $ql;
        $this->binds = collect();
    }

    public function bootstrap()
    {
        $this->registerProviders();
        return $this;
    }

    public function registerProviders()
    {
        foreach ($this->providers as $provider) {
            $this->register(new $provider());
        }
    }

    public function bind(string $name,Closure $provider)
    {
        $this->binds[$name] = $provider;
    }

    public function getBind(string $name)
    {
        if(!$this->binds->offsetExists($name)){
            throw new ServiceNotFoundException("Service: {$name} not found!");
        }
        return $this->binds[$name];
    }

    private function register(ServiceProviderContract $instance)
    {
        $instance->register($this);
    }


}