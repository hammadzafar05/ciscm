<?php
namespace App\Http\Controllers\Student;
use App\Http\Controllers\Controller;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
class ControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $service = (null === $options) ? new $requestedName : new $requestedName($options);
        return $service->setServiceManager($container);
    }
}
