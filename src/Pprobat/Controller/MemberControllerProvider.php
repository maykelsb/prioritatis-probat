<?php
namespace Pprobat\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

use Pprobat\Form\Type\MemberType;

class MemberControllerProvider implements ControllerProviderInterface
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

        $this->listMembers()
            ->editMember()
            ->viewMember();

        return $this->cc;
    }

    protected function listMembers()
    {
        $this->cc->get('/', function(){
            $members = $this->app['db']->fetchAll('SELECT id, username, name, affiliation FROM user');

            return $this->app['twig']->render('members/list.html.twig', [
                'members' => $members
            ]);
        })->bind('members_list');

        return $this;
    }

    protected function editMember()
    {
        $this->cc->match('/edit/{member}', function(Request $request, $member = null){
            $form = $this->app['form.factory']
                ->createBuilder(MemberType::class, $member)
                ->getForm();

            $form->handleRequest($request);

            if (!$form->isValid() || !$form->isSubmitted()) {

                $this->app['session']->getFlashBag()->add(
                    'danger', 'Não foi possível processar sua requisição.'
                );

                return $this->app['twig']->render('members/form.html.twig', [
                    'form' => $form->createView()
                ]);
            }

            $data = $form->getData();
            $this->filterData($data);
            $data['affiliation'] = $data['affiliation']->format('Y-m-d');
            $data['creation'] = $data['creation']->format('Y-m-d');

            if (is_null($member)) {
                $this->app['db']->insert('user', $data);
                $member = $this->app['db']->lastInsertId();
            } else {
                $this->app['db']->update('user', $data, ['id' => $member['id']]);
            }

            $this->app['session']->getFlashBag()->add(
                'success', 'Requisição processada com sucesso.'
            );

            return $this->app->redirect(
                $this->app['url_generator']->generate(
                    'member_view',
                    ['member' => $member['id']]
                )
            );
        })->bind('member_edit')
            ->value('member', null)
            ->convert('member', 'converter.user:convert');

        return $this;
    }

    protected function viewMember()
    {
        $this->cc->get('/view/{member}', function($member){
            return $this->app['twig']->render('members/view.html.twig', [
                'member' => $member
            ]);

        })->bind('member_view')
        ->convert('member', 'converter.user:convert');

        return $this;
    }


    protected function filterData(&$data)
    {
        filter_var_array($data, [
            'name' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'about' => [FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING],
            'username' => [FILTER_SANITIZE_MAGIC_QUOTES],
            'password' => [FILTER_SANITIZE_MAGIC_QUOTES],
        ]);
    }
}
