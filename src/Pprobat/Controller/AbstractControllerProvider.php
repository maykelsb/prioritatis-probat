<?php
namespace Pprobat\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;

abstract class AbstractControllerProvider implements ControllerProviderInterface
{
    /**
     * @var Silex\Application
     */
    protected $app;
    /**
     * @var Silex\ControllerCollection
     */
    protected $cc;

    /**
     * Stablish routes for this controller.
     *
     * @param Silex\Application $app
     * @return Silex\ControllerCollection
     */
    public function connect(Application $app)
    {
        $this->app = $app;
        $this->cc = $app['controllers_factory'];

        $this->enableRoutes();

        return $this->cc;
    }

    /**
     * Use this method to turn on all controller routes.
     */
    abstract protected function enableRoutes();

    /**
     * Sanitize data.
     *
     * @param array $data Data do be sanitized.
     */
    abstract protected function filterData(array &$data);
}
