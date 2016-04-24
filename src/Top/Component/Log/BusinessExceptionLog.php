<?php
namespace Top\Component\Log;

class BusinessExceptionLog
{
    protected $logger;
    
    public function __construct(Symfony\Component\HttpKernel\Log\LoggerInterface $logger) 
    {
        $this->logger = $logger;
    }
    
    
    public function __call($name, $arguments) 
    {
        return $this->logger->$name($arguments);
    }
    
}

