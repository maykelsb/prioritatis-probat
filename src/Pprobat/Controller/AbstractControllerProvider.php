<?php
namespace Pprobat\Controller;

use Symfony\Component\HttpFoundation\Request;
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

    protected $ctrlName;

    public function __construct()
    {
        $this->ctrlName = $this->getControllerName();
    }

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
//
//        $this->advancedEnableRoutes();

        return $this->cc;
    }

    protected function handleForm(Request $request, $initialData)
    {
        $form = $this->app['form.factory']
            ->createBuilder("Pprobat\Form\Type\\{$this->ctrlName}Type", $initialData)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->app['session']->getFlashBag()->add(
                'danger', 'Não foi possível processar sua requisição.'
            );
        }

        $this->ctrlName = strtolower($this->ctrlName);
        if (!$form->isValid() || !$form->isSubmitted()) {
            return $this->app['twig']->render("{$this->ctrlName}/form.html.twig", [
                'form' => $form->createView()
            ]);
        }

        $id = $this->persist($form->getData(), $initialData);
        $this->app['session']->getFlashBag()->add(
            'success', 'Requisição processada com sucesso.'
        );

        return $this->app->redirect(
            $this->app['url_generator']->generate(
                "{$this->ctrlName}_view",
                [$this->ctrlName => $id]
            )
        );
    }

    protected function persist($newData, $initialData)
    {
        $this->filterData($newData);
        $this->prePersist($newData);

        if (!isset($initialData['id'])) {
            $this->app['db']->insert($this->ctrlName, $newData);
            $id = $this->app['db']->lastInsertId();
        } else {
            $this->app['db']->update($this->ctrlName, $newData, ['id' => $initialData['id']]);
            $id = $initialData['id'];
        }

        return $id;
    }

    /**
     * Find the unique controller name.
     *
     * @return string
     */
    protected function getControllerName()
    {
        return str_replace(
            'ControllerProvider',
            '',
            end(explode('\\', get_class($this)))
        );
    }

    /**
     * Loops through all controller methods and find the ones with "Action"
     * at the end of its name, calling them and binding its route.
     *
     * @final
     * @return \Pprobat\Controller\AbstractControllerProvider
     */
    final protected function enableRoutes()
    {
        $refCtrl = new \ReflectionClass($this);
        foreach ($refCtrl->getMethods(\ReflectionMethod::IS_PROTECTED) as $method) {
            if ('Action' === substr($method->name, -6)) {
                $this->{$method->name}();
            }
        }

        return $this;
    }

    /**
     * Sanitize data.
     *
     * @param array $data Data do be sanitized.
     */
    abstract protected function filterData(array &$data);

    /**
     * Handle data before doing persistence.
     */
    abstract protected function prePersist(array &$data);
}
